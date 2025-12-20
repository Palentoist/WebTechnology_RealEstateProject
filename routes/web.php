<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UnitCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\InstallmentPlanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showAuth'])->name('auth');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ✅ Projects: DELETE enabled, Edit/Update disabled
    Route::resource('projects', ProjectController::class)->except(['edit', 'update']);

    // Other resources remain as you had them (no edit/update/delete)
    Route::resource('unit-categories', UnitCategoryController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('units', UnitController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('installment-plans', InstallmentPlanController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('bookings', BookingController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show']);

    // Reminders
    Route::resource('reminders', ReminderController::class)->only(['index', 'create', 'store', 'show']);

    // Send Reminder Now
    Route::post('/reminders/{reminder}/send-now', [ReminderController::class, 'sendNow'])
        ->name('reminders.send-now');
});
