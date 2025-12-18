<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UnitCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\InstallmentPlanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showAuth'])->name('auth');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Exclusive Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('projects', ProjectController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('unit-categories', UnitCategoryController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('installment-plans', InstallmentPlanController::class)->except(['edit', 'update', 'destroy']);

    // Admin-only Write Actions for shared resources
    // MUST come before wildcard resources
    Route::get('units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('units', [UnitController::class, 'store'])->name('units.store');

    Route::get('reminders/create', [ReminderController::class, 'create'])->name('reminders.create');
    Route::post('reminders', [ReminderController::class, 'store'])->name('reminders.store');
});

// Shared Routes (Accessible by both Admin and Customer)
Route::middleware('auth')->group(function () {
    // Notifications
    Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');

    // Bookings: Both can list (filtered) and create (logic handled in controller)
    Route::resource('bookings', BookingController::class)->except(['edit', 'update', 'destroy']);

    // Read-only access for both (Admin has extra write access defined above)
    Route::resource('units', UnitController::class)->only(['index', 'show']);

    // Payments: Shared access for creating (paying) and listing
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/paypal/return', [PaymentController::class, 'paypalReturn'])->name('payments.paypal.return');
    Route::resource('payments', PaymentController::class)->only(['index', 'show']);

    Route::resource('reminders', ReminderController::class)->only(['index', 'show']);
});

// Customer Exclusive Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer-dashboard');
    })->name('customer.dashboard');
});
