<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('delivery_medicines', 'observations')) {
            Schema::table('delivery_medicines', function (Blueprint $table) {
                $table->text('observations')->nullable()->after('included');
            });
        }
    }

    public function down(): void
    {
        Schema::table('delivery_medicines', function (Blueprint $table) {
            $table->dropColumn('observations');
        });
    }
};