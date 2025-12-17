@extends('layouts.app', ['title' => 'New Reminder'])

@section('content')
    <div class="card">
        <h2>Create Reminder</h2>
        @if($installmentItem)
            <p><strong>Booking:</strong> #{{ optional($installmentItem->schedule->booking)->id }}
                - {{ optional(optional($installmentItem->schedule->booking)->user)->name }}</p>
            <p><strong>Installment #{{ $installmentItem->installment_number }}</strong>,
                Due: {{ $installmentItem->due_date }},
                Amount: {{ $installmentItem->amount }},
                Paid: {{ $installmentItem->paid_amount }}</p>
        @endif
        <form method="POST" action="{{ route('reminders.store') }}">
            @csrf
            <input type="hidden" name="installment_item_id"
                   value="{{ old('installment_item_id', optional($installmentItem)->id) }}">
            @error('installment_item_id') <div class="error">{{ $message }}</div> @enderror

            <div class="form-row">
                <label>Reminder Date</label>
                <input type="date" name="reminder_date" value="{{ old('reminder_date', now()->toDateString()) }}">
                @error('reminder_date') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Type</label>
                <input type="text" name="type" value="{{ old('type', 'email') }}">
                @error('type') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Message</label>
                <textarea name="msg">{{ old('msg', 'Installment payment reminder.') }}</textarea>
                @error('msg') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
            <a href="{{ route('reminders.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection


