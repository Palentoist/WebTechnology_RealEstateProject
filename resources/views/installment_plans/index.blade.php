@extends('layouts.app', ['title' => 'Installment Plans'])

@section('content')
    <div class="card">
        <h2>Installment Plans</h2>
        <a href="{{ route('installment-plans.create') }}" class="btn btn-primary">New Plan</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Name</th>
                <th>Duration (months)</th>
                <th>Down Payment %</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($plans as $plan)
                <tr>
                    <td>{{ $plan->id }}</td>
                    <td>{{ optional($plan->project)->name }}</td>
                    <td>{{ $plan->plan_name }}</td>
                    <td>{{ $plan->duration_months }}</td>
                    <td>{{ $plan->down_payment_percentage }}</td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="{{ route('installment-plans.show', $plan) }}" class="btn">View</a>
                            <a href="{{ route('installment-plans.edit', $plan) }}" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No plans found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $plans->links() }}
    </div>
@endsection


