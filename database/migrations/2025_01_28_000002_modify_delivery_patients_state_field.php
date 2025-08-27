<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_patients', function (Blueprint $table) {
            $table->dropColumn('included');
            $table->enum('state', ['programada', 'en_proceso', 'entregada', 'no_entregada'])->default('programada')->after('user_id');
            $table->text('delivery_notes')->nullable()->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_patients', function (Blueprint $table) {
            $table->dropColumn(['state', 'delivery_notes']);
            $table->boolean('included')->default(true)->after('user_id');
        });
    }
};