@extends('layouts.app', ['title' => 'Unit Category'])

@section('content')
    <div class="card">
        <h2>Category: {{ $category->name }}</h2>
        <p><strong>Project:</strong> {{ optional($category->project)->name }}</p>
        <p><strong>Base Price:</strong> {{ $category->base_price }}</p>
        <p><strong>Description:</strong> {{ $category->description }}</p>
        <a href="{{ route('unit-categories.index') }}" class="btn">Back</a>
    </div>

    <div class="card">
        <h2>Units in this Category</h2>
        <a href="{{ route('units.create') }}" class="btn btn-primary">Add Unit</a>
        <table>
            <thead>
            <tr>
                <th>Unit #</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($category->units as $unit)
                <tr>
                    <td>{{ $unit->unit_number }}</td>
                    <td>{{ $unit->price }}</td>
                    <td>{{ $unit->status }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No units yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection


