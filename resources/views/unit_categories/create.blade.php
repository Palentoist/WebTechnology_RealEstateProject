@extends('layouts.app', ['title' => 'New Unit Category'])

@section('content')
    <div class="card">
        <h2>Create Unit Category</h2>
        <form method="POST" action="{{ route('unit-categories.store') }}">
            @csrf
            <div class="form-row">
                <label>Project</label>
                <select name="project_id">
                    <option value="">-- Select Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Description</label>
                <textarea name="description">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div class="form-row">
                <label>Base Price</label>
                <input type="number" step="0.01" name="base_price" value="{{ old('base_price') }}">
                @error('base_price') <div class="error">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
            <a href="{{ route('unit-categories.index') }}" class="btn">Cancel</a>
        </form>
    </div>
@endsection


