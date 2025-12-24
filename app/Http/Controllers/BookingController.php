<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\InstallmentItem;
use App\Models\InstallmentPlan;
use App\Models\InstallmentSchedule;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'unit.project', 'installmentPlan'])->latest()->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = User::orderBy('name')->get();
        $units = Unit::with('project')->orderBy('unit_number')->get();
        $plans = InstallmentPlan::orderBy('plan_name')->get();

        return view('bookings.create', compact('customers', 'units', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'plan_id' => ['required', 'exists:installment_plans,id'],
            'booking_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['pending', 'booked', 'confirmed', 'cancelled'])],
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);
        $plan = InstallmentPlan::findOrFail($validated['plan_id']);

        $totalAmount = $unit->price;
        $downPayment = round($totalAmount * ($plan->down_payment_percentage / 100), 2);
        $remaining = max($totalAmount - $downPayment, 0);

        $booking = Booking::create([
            'user_id' => $validated['user_id'],
            'unit_id' => $validated['unit_id'],
            'plan_id' => $validated['plan_id'],
            'booking_date' => $validated['booking_date'],
            'total_amount' => $totalAmount,
            'down_payment' => $downPayment,
            'status' => $validated['status'],
        ]);

        // Create installment schedule and items
        $totalInstallments = $plan->duration_months;
        $startDate = Carbon::parse($validated['booking_date'])->startOfDay();
        $endDate = (clone $startDate)->addMonths($totalInstallments - 1);

        $schedule = InstallmentSchedule::create([
            'booking_id' => $booking->id,
            'total_installments' => $totalInstallments,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);

        if ($totalInstallments > 0 && $remaining > 0) {
            $perInstallment = round($remaining / $totalInstallments, 2);

            for ($i = 1; $i <= $totalInstallments; $i++) {
                InstallmentItem::create([
                    'schedule_id' => $schedule->id,
                    'installment_number' => $i,
                    'due_date' => (clone $startDate)->addMonths($i - 1)->toDateString(),
                    'amount' => $perInstallment,
                    'paid_amount' => 0,
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('bookings.index')->with('status', 'Booking created successfully with installment schedule.');
    }

    public function edit(Booking $booking)
    {
        $customers = User::orderBy('name')->get();
        $units = Unit::with('project')->orderBy('unit_number')->get();
        $plans = InstallmentPlan::orderBy('plan_name')->get();

        return view('bookings.edit', compact('booking', 'customers', 'units', 'plans'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'plan_id' => ['required', 'exists:installment_plans,id'],
            'booking_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['pending', 'booked', 'confirmed', 'cancelled'])],
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);
        $plan = InstallmentPlan::findOrFail($validated['plan_id']);

        $totalAmount = $unit->price;
        $downPayment = round($totalAmount * ($plan->down_payment_percentage / 100), 2);

        $booking->update([
            'user_id' => $validated['user_id'],
            'unit_id' => $validated['unit_id'],
            'plan_id' => $validated['plan_id'],
            'booking_date' => $validated['booking_date'],
            'total_amount' => $totalAmount,
            'down_payment' => $downPayment,
            'status' => $validated['status'],
        ]);

        return redirect()->route('bookings.index')->with('status', 'Booking updated successfully. Note: Installment schedule was not automatically regenerated.');
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user',
            'unit.project',
            'installmentPlan',
            'installmentSchedule.installmentItems.payments',
        ]);

        return view('bookings.show', compact('booking'));
    }
}


