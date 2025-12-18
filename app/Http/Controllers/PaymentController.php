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
            'token' => ['nullable', 'string'], // Stripe token
        ]);

        $installmentItem = InstallmentItem::findOrFail($validated['installment_item_id']);
        $transactionId = $validated['transaction_id'] ?? null;
        $status = 'success'; // Default for manual

        // Handle Gateways
        try {
            if ($validated['payment_method'] === 'stripe') {
                if (!config('services.stripe.key') || !config('services.stripe.secret')) {
                    throw new \Exception('Stripe is not configured. Please contact the administrator.');
                }

                $gateway = new \App\Services\Payment\StripeGateway();
                $result = $gateway->charge($validated['amount'], 'usd', [
                    'token' => $request->input('token'),
                    'description' => 'Payment for Installment #' . $installmentItem->installment_number,
                    'return_url' => route('payments.show', ['payment' => 0]), // Placeholder, real flow might need adjustment
                ]);

                if (!$result['success']) {
                    return back()->withErrors(['error' => 'Stripe Error: ' . $result['message']]);
                }
                $transactionId = $result['transaction_id'];
            } elseif ($validated['payment_method'] === 'paypal') {
                $mode = config('services.paypal.mode');
                if (!config("services.paypal.{$mode}.client_id") || !config("services.paypal.{$mode}.client_secret")) {
                    throw new \Exception('PayPal is not configured. Please contact the administrator.');
                }

                $gateway = new \App\Services\Payment\PaypalGateway();
                $result = $gateway->charge($validated['amount'], 'usd', [
                    'description' => 'Payment for Installment #' . $installmentItem->installment_number,
                    'return_url' => route('payments.paypal.return', ['installment_item_id' => $installmentItem->id]),
                    'cancel_url' => route('payments.create', ['installment_item_id' => $installmentItem->id]),
                ]);

                if (!$result['success']) {
                    return back()->withErrors(['error' => 'PayPal Error: ' . $result['message']]);
                }

                if (isset($result['redirect_url'])) {
                    return redirect($result['redirect_url']);
                }
                // If direct capture or other flow
                $transactionId = $result['transaction_id'] ?? null;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment Gateway Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }

        // Auto-generate Transaction ID if missing (e.g. Manual payment with no input, or fallback)
        if (empty($transactionId)) {
            $transactionId = 'TXN-' . strtoupper(\Illuminate\Support\Str::random(10));
        }

        $payment = Payment::create([
            'installment_item_id' => $installmentItem->id,
            'payment_date' => $validated['payment_date'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $transactionId,
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

    public function paypalReturn(Request $request)
    {
        $token = $request->get('token');
        //$payerId = $request->get('PayerID'); // Not always needed if using v2 orders structure with srmklive usually

        $gateway = new \App\Services\Payment\PaypalGateway();
        // Determine how to capture. srmklive usually handles capture via provider.
        // For simplicity, we'll try to capture using the token/orderID.
        // In v2, token is often the Order ID.

        $result = $gateway->capture($token);

        if ($result['success']) {
            $installmentItemId = $request->get('installment_item_id');
            $installmentItem = InstallmentItem::findOrFail($installmentItemId);

            // Create Payment Record
            $payment = Payment::create([
                'installment_item_id' => $installmentItem->id,
                'payment_date' => now(),
                'amount' => $result['data']['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? $installmentItem->amount, // fallback or exact
                'payment_method' => 'paypal',
                'transaction_id' => $result['transaction_id'],
                'received_by' => Auth::id(),
            ]);

            $installmentItem->paid_amount += $payment->amount;
            if ($installmentItem->paid_amount >= $installmentItem->amount) {
                $installmentItem->status = 'paid';
            }
            $installmentItem->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'payment_created',
                'title' => 'Payment recorded (PayPal)',
                'details' => 'Payment via PayPal for installment #' . $installmentItem->installment_number,
            ]);

            return redirect()->route('payments.show', $payment)->with('status', 'PayPal Payment successful.');
        }

        return redirect()->route('payments.index')->withErrors(['error' => 'PayPal Payment failed or cancelled.']);
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


