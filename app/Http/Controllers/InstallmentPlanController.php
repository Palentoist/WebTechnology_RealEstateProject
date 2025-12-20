<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPlan;
use App\Models\Project;
use Illuminate\Http\Request;

class InstallmentPlanController extends Controller
{
    public function index()
    {
        $plans = InstallmentPlan::with('project')->latest()->paginate(10);

        return view('installment_plans.index', compact('plans'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();

        return view('installment_plans.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
            'plan_name' => ['required', 'string', 'max:255'],
            'duration_months' => ['required', 'integer', 'min:1'],
            'down_payment_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        InstallmentPlan::create($validated);

        return redirect()->route('installment-plans.index')->with('status', 'Installment plan created successfully.');
    }

    public function show(InstallmentPlan $installment_plan)
    {
        $installment_plan->load('project', 'bookings.user');

        return view('installment_plans.show', ['plan' => $installment_plan]);
    }
}


