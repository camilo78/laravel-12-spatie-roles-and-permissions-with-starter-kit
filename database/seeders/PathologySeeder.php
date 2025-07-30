<?php

namespace Database\Seeders;

use App\Models\Pathology;
use Illuminate\Database\Seeder;

class PathologySeeder extends Seeder
{
    public function run(): void
    {
        $pathologies = [
            ['name' => 'Hipertensión Arterial', 'code' => 'I10', 'description' => 'Presión arterial elevada'],
            ['name' => 'Diabetes Mellitus Tipo 2', 'code' => 'E11', 'description' => 'Diabetes no insulinodependiente'],
            ['name' => 'Asma Bronquial', 'code' => 'J45', 'description' => 'Enfermedad respiratoria crónica'],
            ['name' => 'Artritis Reumatoide', 'code' => 'M06', 'description' => 'Enfermedad autoinmune articular'],
        ];

        foreach ($pathologies as $pathology) {
            Pathology::create($pathology);
        }
    }
}