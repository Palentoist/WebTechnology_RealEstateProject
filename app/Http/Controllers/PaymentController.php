<?php

namespace App\Http\Controllers;

use App\Models\InstallmentItem;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $query = Payment::with(['installmentItem.schedule.booking.user', 'receivedBy']);

        if (Auth::user()->role !== 'admin') {
            // Filter payments related to user's bookings
            $query->whereHas('installmentItem.schedule.booking', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        $payments = $query->latest()->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $installmentItem = null;

        if ($request->has('installment_item_id')) {
            $installmentItem = InstallmentItem::with('schedule.booking.user')->findOrFail($request->get('installment_item_id'));
        }

        return view('payments.create', compact('installmentItem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'installment_item_id' => ['required', 'exists:installment_items,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:50'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'bank_name' => ['nullable', 'string', 'max:255'],
        ]);

        $installmentItem = InstallmentItem::findOrFail($validated['installment_item_id']);

        $payment = Payment::create([
            'installment_item_id' => $installmentItem->id,
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'received_by' => Auth::id(),
        ]);

        // Update installment item summary
        $installmentItem->paid_amount += $payment->amount;
        if ($installmentItem->paid_amount >= $installmentItem->amount) {
            $installmentItem->status = 'paid';
        }
        $installmentItem->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'payment_created',
            'title' => 'Payment recorded',
            'details' => 'Payment of ' . $payment->amount . ' for installment #' . $installmentItem->installment_number . ' (booking #' . optional($installmentItem->schedule->booking)->id . ')',
        ]);

        return redirect()->route('payments.show', $payment)->with('status', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        if (Auth::user()->role !== 'admin') {
            $payment->load('installmentItem.schedule.booking');
            if ($payment->installmentItem->schedule->booking->user_id !== Auth::id()) {
                abort(403);
            }
        }
        $payment->load('installmentItem.schedule.booking.user', 'receivedBy');

        return view('payments.show', compact('payment'));
    }
}


