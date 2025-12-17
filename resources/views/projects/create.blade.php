@extends('layouts.app', ['title' => 'New Project'])

@section('content')
    <div class="card">
        <h2>Create Project</h2>
        <form method="POST" action="{{ route('projects.store') }}">
            @csrf
            <div class="form-row">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Location</label>
                <input type="text" name="location" value="{{ old('location') }}">
                @error('location') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Description</label>
                <textarea name="description">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Total Units</label>
                <input type="number" name="total_units" value="{{ old('total_units', 0) }}">
                @error('total_units') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
            <a href="{{ route('projects.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection


