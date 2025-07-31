<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('dosage');
            $table->integer('quantity');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'suspended', 'completed'])->default('active');
            $table->timestamps();
            
            $table->unique(['user_id', 'medicine_id']);
            $table->index(['user_id', 'medicine_id', 'status'], 'idx_user_medicine_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_medicines');
    }
};