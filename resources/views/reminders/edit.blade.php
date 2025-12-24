@extends('layouts.app', ['title' => 'Edit Reminder'])

@section('content')
    <div class="card">
        <h2>Edit Reminder</h2>
        @if($installmentItem)
            <p><strong>Booking:</strong> #{{ optional($installmentItem->schedule->booking)->id }}
                - {{ optional(optional($installmentItem->schedule->booking)->user)->name }}</p>
            <p><strong>Installment #{{ $installmentItem->installment_number }}</strong>,
                Due: {{ $installmentItem->due_date }},
                Amount: {{ $installmentItem->amount }},
                Paid: {{ $installmentItem->paid_amount }}</p>
        @endif
        <form method="POST" action="{{ route('reminders.update', $reminder) }}">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <label>Reminder Date</label>
                <input type="date" name="reminder_date" value="{{ old('reminder_date', $reminder->reminder_date) }}">
                @error('reminder_date') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Type</label>
                <input type="text" name="type" value="{{ old('type', $reminder->type) }}">
                @error('type') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Message</label>
                <textarea name="msg">{{ old('msg', $reminder->msg) }}</textarea>
                @error('msg') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Status</label>
                <select name="status">
                    <option value="pending" @selected(old('status', $reminder->status) == 'pending')>Pending</option>
                    <option value="sent" @selected(old('status', $reminder->status) == 'sent')>Sent</option>
                    <option value="failed" @selected(old('status', $reminder->status) == 'failed')>Failed</option>
                </select>
                @error('status') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('reminders.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
