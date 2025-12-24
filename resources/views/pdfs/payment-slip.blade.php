<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Slip</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #000; }
        .box { border: 1px solid #000; padding: 12px; }
        .row { margin-bottom: 6px; }
        .label { display: inline-block; width: 140px; font-weight: bold; }
        h2 { margin: 0 0 10px 0; }
        hr { margin: 10px 0; border: none; border-top: 1px solid #000; }
        .muted { color: #444; }
    </style>
</head>
<body>
    <h2>Payment Slip</h2>

    @php
        $booking = optional(optional(optional($payment->installmentItem)->schedule)->booking);
        $customer = optional($booking->user);
        $installmentItem = optional($payment->installmentItem);

        $slipNumber = $slip->slip_number ?? ('SLIP-' . str_pad((string)$payment->id, 6, '0', STR_PAD_LEFT));
        $issuedDate = $slip->issued_date ?? now()->toDateString();

        $paymentDate = $payment->payment_date
            ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')
            : '-';

        $dueDate = $installmentItem->due_date
            ? \Carbon\Carbon::parse($installmentItem->due_date)->format('M d, Y')
            : '-';
    @endphp

    <div class="box">
        <div class="row"><span class="label">Slip #</span> {{ $slipNumber }}</div>
        <div class="row"><span class="label">Issued Date</span> {{ $issuedDate ? \Carbon\Carbon::parse($issuedDate)->format('M d, Y') : '-' }}</div>

        <hr>

        <div class="row"><span class="label">Payment Date</span> {{ $paymentDate }}</div>
        <div class="row"><span class="label">Amount</span> {{ isset($payment->amount) ? number_format($payment->amount, 2) : '-' }}</div>
        <div class="row"><span class="label">Method</span> {{ $payment->payment_method ? strtoupper($payment->payment_method) : '-' }}</div>
        <div class="row"><span class="label">Transaction ID</span> {{ $payment->transaction_id ?? '-' }}</div>
        <div class="row"><span class="label">Bank Name</span> {{ $payment->bank_name ?? '-' }}</div>

        <hr>

        <div class="row"><span class="label">Customer</span> {{ $customer->name ?? '-' }}</div>
        <div class="row"><span class="label">Customer Email</span> {{ $customer->email ?? '-' }}</div>
        <div class="row"><span class="label">Booking ID</span> {{ $booking->id ?? '-' }}</div>
        <div class="row"><span class="label">Installment #</span> {{ $installmentItem->installment_number ?? '-' }}</div>
        <div class="row"><span class="label">Due Date</span> {{ $dueDate }}</div>

        <hr>

        <div class="row"><span class="label">Received By</span> {{ optional($payment->receivedBy)->name ?? '-' }}</div>

        <hr>

        <div class="row muted">
            This is an auto-generated payment slip from the Real Estate Management System.
        </div>
    </div>
</body>
</html>