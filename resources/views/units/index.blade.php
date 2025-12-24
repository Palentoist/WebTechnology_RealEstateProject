@extends('layouts.app', ['title' => 'Units'])

@section('content')
    <div class="card">
        <h2>Units</h2>
        <a href="{{ route('units.create') }}" class="btn btn-primary">New Unit</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Category</th>
                <th>Number</th>
                <th>Price</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($units as $unit)
                <tr>
                    <td>{{ $unit->id }}</td>
                    <td>{{ optional($unit->project)->name }}</td>
                    <td>{{ optional($unit->category)->name }}</td>
                    <td>{{ $unit->unit_number }}</td>
                    <td>{{ $unit->price }}</td>
                    <td>{{ $unit->status }}</td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="{{ route('units.show', $unit) }}" class="btn">View</a>
                            <a href="{{ route('units.edit', $unit) }}" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No units found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $units->links() }}
    </div>
@endsection


