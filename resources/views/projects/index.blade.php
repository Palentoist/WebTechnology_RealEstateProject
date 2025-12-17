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
                <th></th>
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
                    <td><a href="{{ route('projects.show', $project) }}" class="btn">View</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No projects found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $projects->links() }}
    </div>
@endsection


