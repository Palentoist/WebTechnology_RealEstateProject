@extends('layouts.app', ['title' => 'Installment Plan'])

@section('content')
    <div class="card">
        <h2>Plan: {{ $plan->plan_name }}</h2>
        <p><strong>Project:</strong> {{ optional($plan->project)->name }}</p>
        <p><strong>Duration:</strong> {{ $plan->duration_months }} months</p>
        <p><strong>Down Payment %:</strong> {{ $plan->down_payment_percentage }}</p>
        <a href="{{ route('installment-plans.index') }}" class="btn">Back</a>
    </div>

    <div class="card">
        <h2>Bookings Using This Plan</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Unit</th>
                <th>Booking Date</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($plan->bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ optional($booking->user)->name }}</td>
                    <td>{{ optional($booking->unit)->unit_number }}</td>
                    <td>{{ $booking->booking_date }}</td>
                    <td>{{ $booking->status }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No bookings yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection


