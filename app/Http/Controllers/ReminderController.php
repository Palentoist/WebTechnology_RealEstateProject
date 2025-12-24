<?php

namespace App\Http\Controllers;

use App\Models\InstallmentItem;
use App\Models\Reminder;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::with('installmentItem.schedule.booking.user')
            ->latest()
            ->paginate(10);

        return view('reminders.index', compact('reminders'));
    }

    public function create(Request $request)
    {
        $installmentItem = null;

        if ($request->has('installment_item_id')) {
            $installmentItem = InstallmentItem::with('schedule.booking.user')->findOrFail($request->get('installment_item_id'));
        }

        return view('reminders.create', compact('installmentItem'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'installment_item_id' => ['required', 'exists:installment_items,id'],
            'reminder_date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:50'],
            'msg' => ['required', 'string'],
        ]);

        $reminder = Reminder::create([
            'installment_item_id' => $validated['installment_item_id'],
            'reminder_date' => $validated['reminder_date'],
            'type' => $validated['type'],
            'msg' => $validated['msg'],
            'status' => 'pending',
            'sent_at' => null,
        ]);

        ActivityLog::create([
            'user_id' => optional(optional($reminder->installmentItem->schedule)->booking)->user_id,
            'action' => 'reminder_created',
            'title' => 'Reminder created',
            'details' => 'Reminder for installment #'.optional($reminder->installmentItem)->installment_number.' on '.$reminder->reminder_date,
        ]);

        return redirect()->route('reminders.show', $reminder)->with('status', 'Reminder created (no email/SMS sending implemented).');
    }

    public function edit(Reminder $reminder)
    {
        $reminder->load('installmentItem.schedule.booking.user');
        $installmentItem = $reminder->installmentItem;
        return view('reminders.edit', compact('reminder', 'installmentItem'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'reminder_date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:50'],
            'msg' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $reminder->update($validated);

        return redirect()->route('reminders.index')->with('status', 'Reminder updated successfully.');
    }

    public function show(Reminder $reminder)
    {
        $reminder->load('installmentItem.schedule.booking.user');

        return view('reminders.show', compact('reminder'));
    }
}


