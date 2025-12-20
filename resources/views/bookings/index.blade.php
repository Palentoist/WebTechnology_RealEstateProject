@extends('layouts.app', ['title' => 'Bookings'])

@section('content')
    <div class="card">
        <h2>Bookings</h2>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">New Booking</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Unit</th>
                <th>Project</th>
                <th>Plan</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ optional($booking->user)->name }}</td>
                    <td>{{ optional($booking->unit)->unit_number }}</td>
                    <td>{{ optional(optional($booking->unit)->project)->name }}</td>
                    <td>{{ optional($booking->installmentPlan)->plan_name }}</td>
                    <td>{{ $booking->booking_date }}</td>
                    <td>{{ $booking->status }}</td>
                    <td><a href="{{ route('bookings.show', $booking) }}" class="btn">View</a></td>
                </tr>
            @empty
                <tr><td colspan="8">No bookings found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $bookings->links() }}
    </div>
@endsection


