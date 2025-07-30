<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Losartán',
                'commercial_name' => 'Cozaar',
                'generic_name' => 'Losartán Potásico',
                'presentation' => 'Tabletas',
                'concentration' => '50mg'
            ],
            [
                'name' => 'Metformina',
                'commercial_name' => 'Glucophage',
                'generic_name' => 'Metformina Clorhidrato',
                'presentation' => 'Tabletas',
                'concentration' => '500mg'
            ],
            [
                'name' => 'Salbutamol',
                'commercial_name' => 'Ventolin',
                'generic_name' => 'Salbutamol Sulfato',
                'presentation' => 'Inhalador',
                'concentration' => '100mcg/dosis'
            ],
            [
                'name' => 'Ibuprofeno',
                'commercial_name' => null,
                'generic_name' => 'Ibuprofeno',
                'presentation' => 'Tabletas',
                'concentration' => '400mg'
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}