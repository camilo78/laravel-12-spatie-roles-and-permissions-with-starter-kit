<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pathologies', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 10);
            $table->string('descripcion', 256);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathologies');
    }
};