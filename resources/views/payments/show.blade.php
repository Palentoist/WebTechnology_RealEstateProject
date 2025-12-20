@extends('layouts.app', ['title' => 'Payment Details'])

@section('content')
    <div class="card">
        <h2>Payment #{{ $payment->id }}</h2>
        <p><strong>Booking:</strong> #{{ optional(optional($payment->installmentItem->schedule)->booking)->id }}</p>
        <p><strong>Customer:</strong> {{ optional(optional(optional($payment->installmentItem->schedule)->booking)->user)->name }}</p>
        <p><strong>Installment #:</strong> {{ optional($payment->installmentItem)->installment_number }}</p>
        <p><strong>Date:</strong> {{ $payment->payment_date }}</p>
        <p><strong>Amount:</strong> {{ $payment->amount }}</p>
        <p><strong>Method:</strong> {{ $payment->payment_method }}</p>
        <p><strong>Account Number:</strong> {{ $payment->transaction_id }}</p>
        <p><strong>Bank:</strong> {{ $payment->bank_name }}</p>
        <p><strong>Received By:</strong> {{ optional($payment->receivedBy)->name }}</p>
        <a href="{{ route('payments.index') }}" class="btn">Back</a>
    </div>
@endsection


