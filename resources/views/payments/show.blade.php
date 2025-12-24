@extends('layouts.app')

@section('content')
<div class="card">
    {{-- Flash messages --}}
    @if(session('success'))
        <div style="padding: 12px; border-radius: 8px; margin-bottom: 12px; background:#d4edda; color:#155724;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('status'))
        <div style="padding: 12px; border-radius: 8px; margin-bottom: 12px; background:#d4edda; color:#155724;">
            ✅ {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 12px; border-radius: 8px; margin-bottom: 12px; background:#f8d7da; color:#721c24;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom: 16px;">
        <div>
            <h2 style="margin:0;">Payment Details</h2>
            <div style="opacity:.8;">Payment ID: {{ $payment->id }}</div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
    <a href="{{ route('payments.index') }}"
       class="btn"
       style="display:inline-block; padding:10px 14px; border-radius:10px;">
        ⬅ Back to Payments
    </a>

    {{-- ✅ CAN'T-MISS PDF BUTTON (always visible, always works) --}}
    <a href="{{ route('payments.slip-pdf', $payment) }}"
       style="
            display:inline-flex;
            align-items:center;
            gap:10px;
            padding:12px 18px;
            border-radius:14px;
            background:#22c55e;
            color:#ffffff;
            font-weight:800;
            font-size:14px;
            text-decoration:none;
            box-shadow:0 6px 18px rgba(34,197,94,.35);
            border:2px solid rgba(255,255,255,.18);
        ">
        <span style="font-size:18px; line-height:1;">📄</span>
        Generate / Download Slip PDF
    </a>

    {{-- optional helper text --}}
    <span style="opacity:.7; font-size:12px;">
        (Click to generate if missing, otherwise downloads)
    </span>
</div>
    </div>

    {{-- Payment Info --}}
    <div class="card" style="margin-bottom: 16px;">
        <h3 style="margin-top:0;">Payment Info</h3>
        <p><strong>Date:</strong> {{ $payment->payment_date }}</p>
        <p><strong>Amount:</strong> {{ number_format($payment->amount, 2) }}</p>
        <p><strong>Method:</strong> {{ strtoupper($payment->payment_method) }}</p>
        <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? '-' }}</p>
        <p><strong>Bank Name:</strong> {{ $payment->bank_name ?? '-' }}</p>
        <p><strong>Received By:</strong> {{ optional($payment->receivedBy)->name ?? '-' }}</p>
        <p><strong>Created At:</strong> {{ $payment->created_at ?? '-' }}</p>
    </div>

    {{-- Slip Info --}}
    <div class="card" style="margin-bottom: 16px;">
        <h3 style="margin-top:0;">Slip Info</h3>

        @if($payment->paymentSlip)
            <p><strong>Slip Number:</strong> {{ $payment->paymentSlip->slip_number ?? '-' }}</p>
            <p><strong>Issued Date:</strong> {{ $payment->paymentSlip->issued_date ?? '-' }}</p>

            <p>
                <strong>PDF Status:</strong>
                @if($payment->paymentSlip->pdf_path)
                    <span style="color:#28a745; font-weight:bold;">Generated</span>
                @else
                    <span style="color:#ffc107; font-weight:bold;">Not Generated (click button above)</span>
                @endif
            </p>

            <p><strong>PDF Path:</strong> {{ $payment->paymentSlip->pdf_path ?? '-' }}</p>
        @else
            <div style="padding: 12px; border-radius: 8px; background:#fff3cd; color:#856404;">
                ⚠ No slip record found yet. It will be created when you click “Generate / Download Slip PDF”.
            </div>
        @endif
    </div>

    {{-- Installment / Booking / Customer --}}
    @php
        $item     = $payment->installmentItem ?? null;
        $schedule = $item?->schedule ?? null;
        $booking  = $schedule?->booking ?? null;
        $customer = $booking?->user ?? null;
    @endphp

    <div class="card">
        <h3 style="margin-top:0;">Installment / Booking / Customer</h3>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
            <div>
                <h4 style="margin:0 0 8px 0; opacity:.8;">Customer</h4>
                <p><strong>Name:</strong> {{ $customer->name ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $customer->email ?? '-' }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
            </div>

            <div>
                <h4 style="margin:0 0 8px 0; opacity:.8;">Booking</h4>
                <p><strong>Booking ID:</strong> {{ $booking->id ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $booking->status ?? '-' }}</p>
                <p><strong>Created:</strong> {{ $booking->created_at ?? '-' }}</p>
            </div>

            <div>
                <h4 style="margin:0 0 8px 0; opacity:.8;">Installment Item</h4>
                <p><strong>Installment #:</strong> {{ $item->installment_number ?? '-' }}</p>
                <p><strong>Due Date:</strong> {{ $item->due_date ?? '-' }}</p>
                <p><strong>Amount:</strong> {{ isset($item->amount) ? number_format($item->amount, 2) : '-' }}</p>
                <p><strong>Paid Amount:</strong> {{ isset($item->paid_amount) ? number_format($item->paid_amount, 2) : '-' }}</p>
                <p><strong>Status:</strong> {{ $item->status ?? '-' }}</p>
            </div>
        </div>

        <div style="margin-top: 12px;">
            @if($booking && Route::has('bookings.show'))
                <a class="btn" href="{{ route('bookings.show', $booking) }}">View Booking</a>
            @endif
        </div>
    </div>
</div>
@endsection