<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Losartán',
                'generic_name' => 'Losartán Potásico',
                'presentation' => 'Tabletas',
                'concentration' => '50mg'
            ],
            [
                'name' => 'Metformina',
                'generic_name' => 'Metformina Clorhidrato',
                'presentation' => 'Tabletas',
                'concentration' => '500mg'
            ],
            [
                'name' => 'Salbutamol',
                'generic_name' => 'Salbutamol Sulfato',
                'presentation' => 'Inhalador',
                'concentration' => '100mcg/dosis'
            ],
            [
                'name' => 'Ibuprofeno',
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