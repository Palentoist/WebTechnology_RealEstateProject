@extends('layouts.app', ['title' => 'Reminder Details'])

@section('content')
    <div class="card">
        <h2>Reminder #{{ $reminder->id }}</h2>
        <p><strong>Booking:</strong> #{{ optional(optional($reminder->installmentItem->schedule)->booking)->id }}</p>
        <p><strong>Customer:</strong> {{ optional(optional(optional($reminder->installmentItem->schedule)->booking)->user)->name }}</p>
        <p><strong>Installment #:</strong> {{ optional($reminder->installmentItem)->installment_number }}</p>
        <p><strong>Reminder Date:</strong> {{ $reminder->reminder_date }}</p>
        <p><strong>Type:</strong> {{ $reminder->type }}</p>
        <p><strong>Status:</strong> {{ $reminder->status }}</p>
        <p><strong>Message:</strong> {{ $reminder->msg }}</p>
        <a href="{{ route('reminders.index') }}" class="btn">Back</a>
    </div>
@endsection


