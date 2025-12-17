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
    Schema::create('units', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
        $table->foreignId('category_id')->constrained('unit_categories')->onDelete('cascade');
        $table->string('unit_number')->unique();
        $table->decimal('price', 15, 2);
        $table->enum('status', ['available', 'booked', 'sold'])->default('available');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
