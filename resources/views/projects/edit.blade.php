@extends('layouts.app', ['title' => 'Edit Project'])

@section('content')
    <div class="card">
        <h2>Edit Project</h2>
        <form method="POST" action="{{ route('projects.update', $project) }}">
            @csrf
            @method('PUT')
            <div class="form-row">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $project->name) }}">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Location</label>
                <input type="text" name="location" value="{{ old('location', $project->location) }}">
                @error('location') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Description</label>
                <textarea name="description">{{ old('description', $project->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Total Units</label>
                <input type="number" name="total_units" value="{{ old('total_units', $project->total_units) }}">
                @error('total_units') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Update</button>
            <a href="{{ route('projects.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection
