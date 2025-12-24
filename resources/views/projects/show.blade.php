@extends('layouts.app', ['title' => 'Project Details'])

@section('content')
    <div class="card">
        <h2>Project: {{ $project->name }}</h2>
        <p><strong>Location:</strong> {{ $project->location }}</p>
        <p><strong>Total Units:</strong> {{ $project->total_units }}</p>
        <p><strong>Description:</strong> {{ $project->description }}</p>
        <p><strong>Admin:</strong> {{ optional($project->admin)->name }}</p>
        <a href="{{ route('projects.index') }}" class="btn">Back to list</a>
    </div>

    <div class="card">
        <h2>Unit Categories</h2>
        <a href="{{ route('unit-categories.create') }}" class="btn btn-primary">Add Category</a>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Base Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($project->unitCategories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->base_price }}</td>
                    <td><a href="{{ route('unit-categories.show', $category) }}" class="btn">View</a></td>
                </tr>
            @empty
                <tr><td colspan="3">No categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Units</h2>
        <a href="{{ route('units.create') }}" class="btn btn-primary">Add Unit</a>
        <table>
            <thead>
            <tr>
                <th>Number</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($project->units as $unit)
                <tr>
                    <td>{{ $unit->unit_number }}</td>
                    <td>{{ optional($unit->category)->name }}</td>
                    <td>{{ $unit->price }}</td>
                    <td>{{ $unit->status }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No units yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Installment Plans</h2>
        <a href="{{ route('installment-plans.create') }}" class="btn btn-primary">Add Plan</a>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Duration (months)</th>
                <th>Down Payment %</th>
            </tr>
            </thead>
            <tbody>
            @forelse($project->installmentPlans as $plan)
                <tr>
                    <td>{{ $plan->plan_name }}</td>
                    <td>{{ $plan->duration_months }}</td>
                    <td>{{ $plan->down_payment_percentage }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No plans yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection


