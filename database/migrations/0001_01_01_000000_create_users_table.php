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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('dui', 13)->require()->unique();
            $table->string('phone')->nullable();
            $table->foreignId('department_id')
              ->require()
              ->constrained('departments')
              ->cascadeOnDelete();
            $table->foreignId('municipality_id')
              ->require()
              ->constrained('municipalities')
              ->cascadeOnDelete();
            $table->foreignId('locality_id')
              ->require()
              ->constrained('localities')
              ->cascadeOnDelete();
            $table->string('address')->require();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('gender', ['Masculino', 'Femenino'])->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
            $table->index('dui');
            // Si ordenas por 'created_at' o 'id' (común en paginación)
            $table->index('created_at');
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
