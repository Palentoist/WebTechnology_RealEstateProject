<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\InstallmentItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReminderMail;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::latest()->paginate(10);
        return view('reminders.index', compact('reminders'));
    }

    public function create(Request $request)
    {
        $installmentItem = InstallmentItem::with('schedule.booking.user')
            ->findOrFail($request->installment_item_id);

        $booking  = optional($installmentItem->schedule)->booking;
        $customer = optional($booking)->user;

        if (!$booking || !$customer) {
            return redirect()->back()->with('error', 'Booking/Customer not found for this installment.');
        }

        return view('reminders.create', compact('installmentItem', 'booking', 'customer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'installment_item_id' => 'required|exists:installment_items,id',
            'reminder_date'       => 'required|date',
            'msg'                 => 'required|string',
        ]);

        $reminder = Reminder::create([
            'installment_item_id' => $request->installment_item_id,
            'reminder_date'       => $request->reminder_date,
            'msg'                 => $request->msg,
            'type'                => 'email',
            'status'              => 'pending',
        ]);

        return redirect()
            ->route('reminders.show', $reminder)
            ->with('status', 'Reminder created. Click Send Now to notify customer.');
    }

    public function show(Reminder $reminder)
    {
        return view('reminders.show', compact('reminder'));
    }

    public function sendNow(Reminder $reminder)
    {
        // Already sent guard
        if ($reminder->status === 'sent') {
            return back()->with('error', 'This reminder has already been sent.');
        }

        // Load relationships safely
        $installmentItem = $reminder->installmentItem;
        $booking  = optional(optional($installmentItem)->schedule)->booking;
        $customer = optional($booking)->user;

        if (!$installmentItem) {
            return back()->with('error', 'Installment item not found.');
        }

        if (!$booking) {
            return back()->with('error', 'Booking not found for this installment.');
        }

        if (!$customer || !$customer->email) {
            return back()->with('error', 'Customer email not found.');
        }

        try {
            // Send email
            Mail::to($customer->email)->send(
                new PaymentReminderMail($reminder, $installmentItem, $booking, $customer)
            );

            // Update reminder only if send didn't throw
            $reminder->update([
                'status'  => 'sent',
                'sent_at' => now(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $customer->id,
                'action'  => 'reminder_sent',
                'title'   => 'Reminder Sent',
                'details' => 'Payment reminder emailed to ' . $customer->email .
                             ' for installment #' . $installmentItem->installment_number,
            ]);

            return back()->with('status', 'Reminder sent successfully to ' . $customer->email);

        } catch (\Exception $e) {
            // Do NOT mark as sent if mail fails
            return back()->with('error', 'Email NOT sent: ' . $e->getMessage());
        }
    }
}
