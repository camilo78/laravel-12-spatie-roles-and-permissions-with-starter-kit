<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('code')->unique(); // Código del departamento
            $table->string('name'); // Nombre del departamento
            $table->timestamps();
            $table->index('name'); // Índice para búsquedas rápidas por nombre
            $table->index('created_at'); // Índice para ordenación por fecha de creación
            $table->index('id'); // Índice para búsquedas rápidas por ID
            
        });
    }

    public function down(): void {
        Schema::dropIfExists('departments');
    }
};