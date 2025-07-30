<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_delivery_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('included')->default(true);
            $table->timestamps();
            
            $table->unique(['medicine_delivery_id', 'user_id'], 'delivery_patient_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_patients');
    }
};