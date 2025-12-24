<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UnitCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\InstallmentPlanController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReminderController;

Route::get('/', [AuthController::class, 'showAuth'])->name('auth');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('projects', ProjectController::class)->except(['destroy']);
    Route::resource('unit-categories', UnitCategoryController::class)->except(['destroy']);
    Route::resource('units', UnitController::class)->except(['destroy']);
    Route::resource('installment-plans', InstallmentPlanController::class)->except(['destroy']);
    Route::resource('bookings', BookingController::class)->except(['destroy']);

    Route::resource('payments', PaymentController::class)->except(['destroy']);

    // ✅ Payment Slip PDF (Generate & Download)
    Route::get('/payments/{payment}/slip-pdf', [PaymentController::class, 'slipPdf'])
        ->name('payments.slip-pdf');

    Route::resource('reminders', ReminderController::class)->except(['destroy']);

    Route::post('/reminders/{reminder}/send-now', [ReminderController::class, 'sendNow'])
        ->name('reminders.send-now');
});