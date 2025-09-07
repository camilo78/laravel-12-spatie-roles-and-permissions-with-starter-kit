<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_name')->default('Hospital General Atlántida');
            $table->string('program_name')->default('Programa de Entrega de Medicamentos en Casa');
            $table->string('program_manager')->default('Coordinadora del Programa');
            $table->string('app_logo')->nullable();
            $table->string('hospital_logo')->nullable();
            $table->integer('first_delivery_days')->default(30);
            $table->integer('subsequent_delivery_days')->default(120);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};