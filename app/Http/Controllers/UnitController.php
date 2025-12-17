<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function index()
    {
        $query = Unit::with(['project', 'category']);

        // If we want customers to only see available units:
        if (Auth::check() && Auth::user()->role !== 'admin') {
            $query->where('status', 'Available');
        }

        $units = $query->latest()->paginate(10);

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

    public function show(Unit $unit)
    {
        $unit->load('project', 'category', 'bookings.user');

        return view('units.show', compact('unit'));
    }
}


