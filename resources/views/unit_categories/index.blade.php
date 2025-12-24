@extends('layouts.app', ['title' => 'Unit Categories'])

@section('content')
    <div class="card">
        <h2>Unit Categories</h2>
        <a href="{{ route('unit-categories.create') }}" class="btn btn-primary">New Category</a>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Name</th>
                <th>Base Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ optional($category->project)->name }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->base_price }}</td>
                    <td>
                        <div style="display: flex; gap: 4px;">
                            <a href="{{ route('unit-categories.show', $category) }}" class="btn">View</a>
                            <a href="{{ route('unit-categories.edit', $category) }}" class="btn">Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No categories found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $categories->links() }}
    </div>
@endsection


