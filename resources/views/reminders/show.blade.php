@extends('layouts.app', ['title' => 'Reminder Details'])

@section('content')
<div class="card">
    <h2>Reminder #{{ $reminder->id }}</h2>

    @if(session('status'))
        <div style="padding: 15px; background: #bfd8c5f1; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
            ✅ {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
            ❌ {{ session('error') }}
        </div>
    @endif

    @php
        $booking = optional(optional($reminder->installmentItem?->schedule)->booking);
        $customer = optional($booking->user);

        $sentAt = $reminder->sent_at
            ? \Carbon\Carbon::parse($reminder->sent_at)->format('M d, Y h:i A')
            : null;
    @endphp

    <p><strong>Booking:</strong> #{{ $booking->id ?? '-' }}</p>
    <p><strong>Customer:</strong> {{ $customer->name ?? '-' }}</p>
    <p><strong>Customer Email:</strong> {{ $customer->email ?? '-' }}</p>
    <p><strong>Installment #:</strong> {{ optional($reminder->installmentItem)->installment_number ?? '-' }}</p>
    <p><strong>Reminder Date:</strong> {{ $reminder->reminder_date }}</p>
    <p><strong>Type:</strong> {{ $reminder->type }}</p>

    <p><strong>Status:</strong>
        <span style="color: {{ $reminder->status === 'sent' ? '#22c55e' : '#f59e0b' }}; font-weight: bold; font-size: 16px;">
            {{ strtoupper($reminder->status) }}
        </span>
    </p>

    @if($sentAt)
        <p><strong>Sent At:</strong> {{ $sentAt }}</p>
    @endif

    <p><strong>Message:</strong> {{ $reminder->msg }}</p>

    <div style="margin-top: 20px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        @if($reminder->status === 'pending')
            <form method="POST" action="{{ route('reminders.send-now', $reminder) }}" style="display: inline;">
                @csrf
                <button type="submit"
                        onclick="return confirm('Send this reminder now via email to {{ $customer->email ?? 'customer' }}?')"
                        style="
                            background:#488daa91;
                            border:none;
                            color:#fff;
                            padding:10px 18px;
                            border-radius:999px;
                            font-weight:bold;
                            cursor:pointer;
                        ">
                    📧 Send Now
                </button>
            </form>
        @else
            <button disabled
                    style="
                        background:#488daa91;
                        border:none;
                        color:#fff;
                        padding:10px 18px;
                        border-radius:999px;
                        font-weight:bold;
                        cursor:not-allowed;
                        opacity:.85;
                    ">
                ✅ Already Sent
            </button>
        @endif

        <a href="{{ route('reminders.index') }}" class="btn">Back to List</a>
    </div>
</div>
@endsection
