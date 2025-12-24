<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitCategory;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with(['project', 'category'])->latest()->paginate(10);

        return view('units.index', compact('units'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $categories = UnitCategory::orderBy('name')->get();

        return view('units.create', compact('projects', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'category_id' => ['required', 'exists:unit_categories,id'],
            'unit_number' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')->with('status', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        $projects = Project::orderBy('name')->get();
        $categories = UnitCategory::orderBy('name')->get();

        return view('units.edit', compact('unit', 'projects', 'categories'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'category_id' => ['required', 'exists:unit_categories,id'],
            'unit_number' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')->with('status', 'Unit updated successfully.');
    }

    public function show(Unit $unit)
    {
        $unit->load('project', 'category', 'bookings.user');

        return view('units.show', compact('unit'));
    }
}


