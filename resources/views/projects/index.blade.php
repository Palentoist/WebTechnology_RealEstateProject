@extends('layouts.app', ['title' => 'Projects'])

@section('content')
    <div class="card">
        <h2>Projects</h2>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>

        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Total Units</th>
                <th>Admin</th>
                <th>View</th>
                <th>Delete</th>
            </tr>
            </thead>

            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->location }}</td>
                    <td>{{ $project->total_units }}</td>
                    <td>{{ optional($project->admin)->name }}</td>

                    {{-- VIEW COLUMN --}}
                    <td style="text-align: center;">
                        <a href="{{ route('projects.show', $project) }}">
                            @if($project->image)
                                <img src="{{ asset('storage/' . $project->image) }}"
                                     style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            @else
                                <div style="width:60px;height:60px;background:#e2e8f0;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    📷
                                </div>
                            @endif
                        </a>
                    </td>

                    {{-- DELETE COLUMN --}}
                    <td style="text-align:center;">
                        <form action="{{ route('projects.destroy', $project) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn"
                                    style="background:#dc3545;color:white;padding:6px 10px;">
                                🗑 Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;">No projects found.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $projects->links() }}
    </div>
@endsection
