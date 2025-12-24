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
                <th>Received By</th>
                <th>Slip PDF</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ optional(optional(optional($payment->installmentItem)->schedule)->booking)->id }}</td>
                    <td>{{ optional(optional(optional(optional($payment->installmentItem)->schedule)->booking)->user)->name }}</td>
                    <td>{{ optional($payment->installmentItem)->installment_number }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ optional($payment->receivedBy)->name }}</td>

                    <td style="white-space: nowrap;">
                        <a href="{{ route('payments.slip-pdf', $payment) }}" class="btn btn-primary" style="padding:6px 10px;">
                            📄 Slip PDF
                        </a>
                    </td>

                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="{{ route('payments.show', $payment) }}" class="btn">View</a>
                            <a href="{{ route('payments.edit', $payment) }}" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10">No payments recorded.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $payments->links() }}
    </div>
@endsection