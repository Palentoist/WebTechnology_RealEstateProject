<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'name'        => ['required', 'string', 'max:255'],
            'location'    => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'total_units' => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
        }

        Project::create([
            'admin_id'    => Auth::id(),
            'name'        => $validated['name'],
            'location'    => $validated['location'],
            'description' => $validated['description'] ?? null,
            'total_units' => $validated['total_units'],
            'image'       => $imagePath,
        ]);

        return redirect()->route('projects.index')->with('status', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['units.category', 'unitCategories', 'installmentPlans']);
        return view('projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        if ($project->image && Storage::disk('public')->exists($project->image)) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('status', 'Project deleted successfully.');
    }
}
