<?php

namespace App\Http\Controllers;

use App\Models\InstallmentItem;
use App\Models\Payment;
use App\Models\PaymentSlip;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['installmentItem.schedule.booking.user', 'receivedBy', 'paymentSlip'])
            ->latest()
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $installmentItem = null;

        if ($request->has('installment_item_id')) {
            $installmentItem = InstallmentItem::with('schedule.booking.user')
                ->findOrFail($request->get('installment_item_id'));
        }

        return view('payments.create', compact('installmentItem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'installment_item_id' => ['required', 'exists:installment_items,id'],
            'payment_date'        => ['required', 'date'],
            'amount'              => ['required', 'numeric', 'min:0.01'],
            'payment_method'      => ['required', 'string', 'max:50'],
            'transaction_id'      => ['nullable', 'string', 'max:255'],
            'bank_name'           => ['nullable', 'string', 'max:255'],
        ]);

        return DB::transaction(function () use ($validated) {

            $installmentItem = InstallmentItem::findOrFail($validated['installment_item_id']);

            // 1) Create payment
            $payment = Payment::create([
                'installment_item_id' => $installmentItem->id,
                'payment_date'        => $validated['payment_date'],
                'amount'              => $validated['amount'],
                'payment_method'      => $validated['payment_method'],
                'transaction_id'      => $validated['transaction_id'] ?? null,
                'bank_name'           => $validated['bank_name'] ?? null,
                'received_by'         => Auth::id(),
            ]);

            // 2) Update installment item
            $installmentItem->paid_amount += $payment->amount;

            if ($installmentItem->paid_amount >= $installmentItem->amount) {
                $installmentItem->status = 'paid';
            }

            $installmentItem->save();

            // 3) Activity log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action'  => 'payment_created',
                'title'   => 'Payment recorded',
                'details' => 'Payment of ' . $payment->amount
                    . ' for installment #' . $installmentItem->installment_number
                    . ' (booking #' . optional($installmentItem->schedule->booking)->id . ')',
            ]);

            // 4) Create slip row ONLY (no PDF generation here)
            PaymentSlip::firstOrCreate(
                ['payment_id' => $payment->id],
                [
                    'slip_number' => 'SLIP-' . now()->format('Ymd') . '-' . str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT),
                    'issued_date' => now()->toDateString(),
                    'pdf_path'    => null,
                ]
            );

            return redirect()
                ->route('payments.show', $payment)
                ->with('status', 'Payment saved successfully. Click "Generate / Download Slip PDF" to get the PDF.');
        });
    }

    // ✅ Generate (if missing) + Download Payment Slip PDF
    public function slipPdf(Payment $payment)
    {
        $payment->load('installmentItem.schedule.booking.user', 'receivedBy', 'paymentSlip');

        // If slip exists AND pdf exists => just download
        if (
            $payment->paymentSlip &&
            $payment->paymentSlip->pdf_path &&
            Storage::disk('public')->exists($payment->paymentSlip->pdf_path)
        ) {
            return Storage::disk('public')->download(
                $payment->paymentSlip->pdf_path,
                $payment->paymentSlip->slip_number . '.pdf'
            );
        }

        // Create slip if missing
        if (!$payment->paymentSlip) {
            $payment->paymentSlip()->create([
                'slip_number' => 'SLIP-' . now()->format('Ymd') . '-' . str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT),
                'issued_date' => now()->toDateString(),
                'pdf_path'    => null,
            ]);

            $payment->load('paymentSlip');
        }

        $slip = $payment->paymentSlip;

        // Generate PDF from your existing template
        $pdf = Pdf::loadView('pdfs.payment-slip', [
            'payment' => $payment,
            'slip'    => $slip,
        ]);

        $path = 'payment-slips/' . $slip->slip_number . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $slip->update(['pdf_path' => $path]);

        return Storage::disk('public')->download(
            $path,
            $slip->slip_number . '.pdf'
        );
    }

    public function edit(Payment $payment)
    {
        $payment->load('installmentItem.schedule.booking.user');
        $installmentItem = $payment->installmentItem;
        return view('payments.edit', compact('payment', 'installmentItem'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_date'        => ['required', 'date'],
            'amount'              => ['required', 'numeric', 'min:0.01'],
            'payment_method'      => ['required', 'string', 'max:50'],
            'transaction_id'      => ['nullable', 'string', 'max:255'],
            'bank_name'           => ['nullable', 'string', 'max:255'],
        ]);

        return DB::transaction(function () use ($payment, $validated) {
            $oldAmount = $payment->amount;
            $newAmount = $validated['amount'];
            $installmentItem = $payment->installmentItem;

            // Revert old amount from installment item
            $installmentItem->paid_amount -= $oldAmount;

            // Update payment
            $payment->update([
                'payment_date'   => $validated['payment_date'],
                'amount'         => $newAmount,
                'payment_method' => $validated['payment_method'],
                'transaction_id' => $validated['transaction_id'] ?? null,
                'bank_name'      => $validated['bank_name'] ?? null,
            ]);

            // Add new amount to installment item
            $installmentItem->paid_amount += $newAmount;

            // Update status
            if ($installmentItem->paid_amount >= $installmentItem->amount) {
                $installmentItem->status = 'paid';
            } else {
                $installmentItem->status = 'pending';
            }
            $installmentItem->save();

            // Invalidate existing PDF slip if any, so it regenerates with new info
            if ($payment->paymentSlip && $payment->paymentSlip->pdf_path) {
                Storage::disk('public')->delete($payment->paymentSlip->pdf_path);
                $payment->paymentSlip->update(['pdf_path' => null]);
            }

            return redirect()->route('payments.index')->with('status', 'Payment updated successfully.');
        });
    }

    public function show(Payment $payment)
    {
        $payment->load('installmentItem.schedule.booking.user', 'receivedBy', 'paymentSlip');
        return view('payments.show', compact('payment'));
    }
}