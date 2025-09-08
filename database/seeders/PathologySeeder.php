<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PathologySeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = database_path('sql/cie-10.sql');
        
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            DB::unprepared($sql);
        }
    }
}