<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zone;
use App\Models\Municipality;

class ZoneSeeder extends Seeder
{
    public function run(): void
    {
        $municipalities = Municipality::all();

        foreach ($municipalities as $municipality) {
            Zone::create([
                'name' => 'Sector 1',
                'description' => 'Oeste',
                'municipality_id' => $municipality->id,
            ]);

            Zone::create([
                'name' => 'Sector 2',
                'description' => 'Norte',
                'municipality_id' => $municipality->id,
            ]);

            Zone::create([
                'name' => 'Sector 3',
                'description' => 'Sur',
                'municipality_id' => $municipality->id,
            ]);

            Zone::create([
                'name' => 'Sector 4',
                'description' => 'Noreste',
                'municipality_id' => $municipality->id,
            ]);

            Zone::create([
                'name' => 'Sector 5',
                'description' => 'Este',
                'municipality_id' => $municipality->id,
            ]);
        }
    }
}
