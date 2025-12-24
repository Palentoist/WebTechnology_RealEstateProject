@extends('layouts.app', ['title' => 'Edit Payment'])

@section('content')
    <div class="card">
        <h2>Edit Payment</h2>

        @if($installmentItem)
            <p>
                <strong>Booking:</strong>
                #{{ optional($installmentItem->schedule->booking)->id }}
                - {{ optional(optional($installmentItem->schedule->booking)->user)->name }}
            </p>

            <p>
                <strong>Installment #{{ $installmentItem->installment_number }}</strong>,
                Due: {{ $installmentItem->due_date }},
                Amount: {{ $installmentItem->amount }},
                Paid: {{ $installmentItem->paid_amount }}
            </p>
        @endif

        <form method="POST" action="{{ route('payments.update', $payment) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <label>Payment Date</label>
                <input type="date" name="payment_date" value="{{ old('payment_date', $payment->payment_date) }}">
                @error('payment_date') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" value="{{ old('amount', $payment->amount) }}">
                @error('amount') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <label>Payment Method</label>
                <input type="text" name="payment_method" value="{{ old('payment_method', $payment->payment_method) }}">
                @error('payment_method') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <label>Transaction ID / Account No.</label>
                <input type="text" name="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}">
                @error('transaction_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-row">
                <label>Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $payment->bank_name) }}">
                @error('bank_name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('payments.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
