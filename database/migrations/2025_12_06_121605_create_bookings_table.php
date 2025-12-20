<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
        $table->foreignId('plan_id')->constrained('installment_plans')->onDelete('cascade');
        $table->date('booking_date');
        $table->decimal('total_amount', 15, 2);
        $table->decimal('down_payment', 15, 2);
        // Booking lifecycle: pending -> booked/confirmed -> cancelled
        $table->enum('status', ['pending', 'booked', 'confirmed', 'cancelled'])->default('booked');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
