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
            $table->bigInteger('level');
            $table->string('code', 1024);
            $table->string('description', 1024);
            $table->string('code_0', 1024)->nullable();
            $table->string('code_1', 1024)->nullable();
            $table->string('code_2', 1024)->nullable();
            $table->string('code_3', 1024)->nullable();
            $table->string('code_4', 1024)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pathologies');
    }
};