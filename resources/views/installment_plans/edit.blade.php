@extends('layouts.app', ['title' => 'Edit Installment Plan'])

@section('content')
    <div class="card">
        <h2>Edit Installment Plan</h2>
        <form method="POST" action="{{ route('installment-plans.update', $plan) }}">
            @csrf
            @method('PUT')
            <div class="form-row">
                <label>Project</label>
                <select name="project_id">
                    <option value="">-- Select Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id', $plan->project_id) == $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Plan Name</label>
                <input type="text" name="plan_name" value="{{ old('plan_name', $plan->plan_name) }}">
                @error('plan_name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Duration (months)</label>
                <input type="number" name="duration_months" value="{{ old('duration_months', $plan->duration_months) }}">
                @error('duration_months') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Down Payment Percentage</label>
                <input type="number" step="0.01" name="down_payment_percentage" value="{{ old('down_payment_percentage', $plan->down_payment_percentage) }}">
                @error('down_payment_percentage') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('installment-plans.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
