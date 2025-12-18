@extends('layouts.app', ['title' => 'Payments'])

@section('content')
    <div class="card">
        <h2>Payments</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Booking</th>
                    <th>Customer</th>
                    <th>Installment #</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Received By</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ optional(optional(optional($payment->installmentItem)->schedule)->booking)->id }}</td>
                        <td>{{ optional(optional(optional(optional($payment->installmentItem)->schedule)->booking)->user)->name }}
                        </td>
                        <td>{{ optional($payment->installmentItem)->installment_number }}</td>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->transaction_id }}</td>
                        <td>{{ optional($payment->receivedBy)->name }}</td>
                        <td><a href="{{ route('payments.show', $payment) }}" class="btn">View</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No payments recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $payments->links() }}
    </div>
@endsection