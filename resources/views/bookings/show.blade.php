@extends('layouts.app', ['title' => 'Booking Details'])

@section('content')
    <div class="card">
        <h2>Booking #{{ $booking->id }}</h2>
        <p><strong>Customer:</strong> {{ optional($booking->user)->name }} ({{ optional($booking->user)->email }})</p>
        <p><strong>Unit:</strong> {{ optional($booking->unit)->unit_number }} - {{ optional(optional($booking->unit)->project)->name }}</p>
        <p><strong>Plan:</strong> {{ optional($booking->installmentPlan)->plan_name }}</p>
        <p><strong>Booking Date:</strong> {{ $booking->booking_date }}</p>
        <p><strong>Total Amount:</strong> {{ $booking->total_amount }}</p>
        <p><strong>Down Payment:</strong> {{ $booking->down_payment }}</p>
        <p><strong>Status:</strong> {{ $booking->status }}</p>
        <a href="{{ route('bookings.index') }}" class="btn">Back</a>
    </div>

    @if($booking->installmentSchedule)
        <div class="card">
            <h2>Installment Schedule</h2>
            <p><strong>Total Installments:</strong> {{ $booking->installmentSchedule->total_installments }}</p>
            <p><strong>Period:</strong> {{ $booking->installmentSchedule->start_date }} - {{ $booking->installmentSchedule->end_date }}</p>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Paid</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($booking->installmentSchedule->installmentItems as $item)
                    <tr>
                        <td>{{ $item->installment_number }}</td>
                        <td>{{ $item->due_date }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $item->paid_amount }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <a href="{{ route('payments.create', ['installment_item_id' => $item->id]) }}" class="btn">Add Payment</a>
                            <a href="{{ route('reminders.create', ['installment_item_id' => $item->id]) }}" class="btn">Create Reminder</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection


