<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('admin')->latest()->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'total_units' => ['required', 'integer', 'min:0'],
        ]);

        Project::create([
            'admin_id' => Auth::id(),
            'name' => $validated['name'],
            'location' => $validated['location'],
            'description' => $validated['description'] ?? null,
            'total_units' => $validated['total_units'],
        ]);

        return redirect()->route('projects.index')->with('status', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'total_units' => ['required', 'integer', 'min:0'],
        ]);

        $project->update([
            'name' => $validated['name'],
            'location' => $validated['location'],
            'description' => $validated['description'] ?? null,
            'total_units' => $validated['total_units'],
        ]);

        return redirect()->route('projects.index')->with('status', 'Project updated successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['units.category', 'unitCategories', 'installmentPlans']);

        return view('projects.show', compact('project'));
    }
}


