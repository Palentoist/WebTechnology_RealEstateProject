@extends('layouts.app', ['title' => 'Edit Unit'])

@section('content')
    <div class="card">
        <h2>Edit Unit</h2>
        <form method="POST" action="{{ route('units.update', $unit) }}">
            @csrf
            @method('PUT')
            <div class="form-row">
                <label>Project</label>
                <select name="project_id">
                    <option value="">-- Select Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id', $unit->project_id) == $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Category</label>
                <select name="category_id">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $unit->category_id) == $category->id)>
                            {{ $category->name }} ({{ optional($category->project)->name }})
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Unit Number</label>
                <input type="text" name="unit_number" value="{{ old('unit_number', $unit->unit_number) }}">
                @error('unit_number') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $unit->price) }}">
                @error('price') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Status</label>
                <input type="text" name="status" value="{{ old('status', $unit->status) }}">
                @error('status') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('units.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
