@extends('layouts.app', ['title' => 'New Project'])

@section('content')
    <div class="card">
        <h2>Create Project</h2>
        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
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
            {{-- ADDED IMAGE UPLOAD --}}
            <div class="form-row">
                <label>Project Image</label>
                <input type="file" name="image" accept="image/*" id="imageInput">
                <small style="color: #666;">Optional: JPEG, PNG, JPG, GIF (Max 2MB)</small>
                @error('image') <div class="error">{{ $message }}</div> @enderror
                <img id="preview" style="display: none; margin-top: 10px; max-width: 200px; border-radius: 8px;">
            </div>
            {{-- END ADDED --}}
            <button class="btn btn-primary" type="submit">Save</button>
            <a href="{{ route('projects.index') }}" class="btn">Cancel</a>
        </form>
    </div>

    {{-- ADDED IMAGE PREVIEW SCRIPT --}}
    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    {{-- END ADDED --}}
@endsection