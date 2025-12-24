<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\UnitCategory;
use Illuminate\Http\Request;

class UnitCategoryController extends Controller
{
    public function index()
    {
        $categories = UnitCategory::with('project')->latest()->paginate(10);

        return view('unit_categories.index', compact('categories'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();

        return view('unit_categories.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
        ]);

        UnitCategory::create($validated);

        return redirect()->route('unit-categories.index')->with('status', 'Unit category created successfully.');
    }

    public function edit(UnitCategory $unitCategory)
    {
        $projects = Project::orderBy('name')->get();

        return view('unit_categories.edit', compact('unitCategory', 'projects'));
    }

    public function update(Request $request, UnitCategory $unitCategory)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
        ]);

        $unitCategory->update($validated);

        return redirect()->route('unit-categories.index')->with('status', 'Unit category updated successfully.');
    }

    public function show(UnitCategory $unitCategory)
    {
        $unitCategory->load('project', 'units');

        return view('unit_categories.show', ['category' => $unitCategory]);
    }
}


