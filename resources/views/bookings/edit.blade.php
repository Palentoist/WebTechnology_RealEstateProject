@extends('layouts.app', ['title' => 'Edit Booking'])

@section('content')
    <div class="card">
        <h2>Edit Booking</h2>
        <form method="POST" action="{{ route('bookings.update', $booking) }}">
            @csrf
            @method('PUT')
            <div class="form-row">
                <label>Customer</label>
                <select name="user_id">
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('user_id', $booking->user_id) == $customer->id)>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Unit</label>
                <select name="unit_id">
                    <option value="">-- Select Unit --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" @selected(old('unit_id', $booking->unit_id) == $unit->id)>
                            {{ optional($unit->project)->name }} - {{ $unit->unit_number }} ({{ $unit->price }})
                        </option>
                    @endforeach
                </select>
                @error('unit_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Installment Plan</label>
                <select name="plan_id">
                    <option value="">-- Select Plan --</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" @selected(old('plan_id', $booking->plan_id) == $plan->id)>
                            {{ $plan->plan_name }} ({{ $plan->duration_months }} months, DP {{ $plan->down_payment_percentage }}%)
                        </option>
                    @endforeach
                </select>
                @error('plan_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Booking Date</label>
                <input type="date" name="booking_date" value="{{ old('booking_date', $booking->booking_date) }}">
                @error('booking_date') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Status</label>
                <input type="text" name="status" value="{{ old('status', $booking->status) }}">
                @error('status') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('bookings.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
