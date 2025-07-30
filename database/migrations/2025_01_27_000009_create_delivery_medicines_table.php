<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_medicine_id')->constrained()->cascadeOnDelete();
            $table->boolean('included')->default(true);
            $table->timestamps();
            
            $table->unique(['delivery_patient_id', 'patient_medicine_id'], 'delivery_medicine_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_medicines');
    }
};