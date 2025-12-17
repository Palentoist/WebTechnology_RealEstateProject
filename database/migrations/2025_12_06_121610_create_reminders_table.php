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
    Schema::create('reminders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('installment_item_id')->constrained('installment_items')->onDelete('cascade');
        $table->date('reminder_date');
        $table->enum('type', ['email', 'sms']);
        $table->text('msg')->nullable();
        $table->enum('status', ['pending', 'sent'])->default('pending');
        $table->timestamp('sent_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
