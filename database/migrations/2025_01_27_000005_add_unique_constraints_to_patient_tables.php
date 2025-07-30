<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_pathologies', function (Blueprint $table) {
            $table->unique(['user_id', 'pathology_id'], 'unique_user_pathology');
        });

        Schema::table('patient_medicines', function (Blueprint $table) {
            $table->index(['patient_pathology_id', 'medicine_id', 'status'], 'idx_pathology_medicine_status');
        });
    }

    public function down(): void
    {
        Schema::table('patient_pathologies', function (Blueprint $table) {
            $table->dropUnique('unique_user_pathology');
        });

        Schema::table('patient_medicines', function (Blueprint $table) {
            $table->dropIndex('idx_pathology_medicine_status');
        });
    }
};