@extends('layouts.app', ['title' => 'Customer Dashboard'])

@section('content')
    <div class="card">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
        <div class="card-subtitle">
            Manage your bookings and view available properties.
        </div>

        <div class="card-grid">
            <div class="metric-card">
                <div class="metric-label">Available Units</div>
                <div class="metric-value">{{ \App\Models\Unit::where('status', 'available')->count() }}</div>
                <div class="metric-trend">Units ready for booking.</div>
                <div style="margin-top:10px;">
                    <a href="{{ route('units.index') }}" class="btn btn-primary">Browse Properties</a>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-label">My Bookings</div>
                <div class="metric-value">{{ \App\Models\Booking::where('user_id', Auth::id())->count() }}</div>
                <div class="metric-trend">Your active property bookings.</div>
                <div style="margin-top:10px;">
                    <a href="{{ route('bookings.index') }}" class="btn btn-primary">My Bookings</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Recent Activity</h2>
         <!-- Placeholder for user specific activity or notifications -->
        <p style="color: #9ca3af; margin-top: 10px;">No recent alerts.</p>
    </div>
@endsection
