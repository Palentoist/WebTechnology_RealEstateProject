@extends('layouts.app', ['title' => 'Unit Details'])

@section('content')
    <div class="card">
        <h2>Unit {{ $unit->unit_number }}</h2>
        <p><strong>Project:</strong> {{ optional($unit->project)->name }}</p>
        <p><strong>Category:</strong> {{ optional($unit->category)->name }}</p>
        <p><strong>Price:</strong> {{ $unit->price }}</p>
        <p><strong>Status:</strong> {{ $unit->status }}</p>
        <a href="{{ route('units.index') }}" class="btn">Back</a>
    </div>

    <div class="card">
        <h2>Bookings for this Unit</h2>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">New Booking</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Plan</th>
                <th>Booking Date</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($unit->bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ optional($booking->user)->name }}</td>
                    <td>{{ optional($booking->installmentPlan)->plan_name }}</td>
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


