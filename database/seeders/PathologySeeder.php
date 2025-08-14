<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PathologySeeder extends Seeder
{
    public function run(): void
    {
        $pathologies = [
            ['clave' => 'I10', 'descripcion' => 'Hipertensión esencial (primaria)'],
            ['clave' => 'E10', 'descripcion' => 'Diabetes mellitus insulinodependiente'],
            ['clave' => 'E11', 'descripcion' => 'Diabetes mellitus no insulinodependiente'],
            ['clave' => 'G40', 'descripcion' => 'Epilepsia'],
            ['clave' => 'E03', 'descripcion' => 'Otros hipotiroidismos'],
            ['clave' => 'E05', 'descripcion' => 'Tirotoxicosis [hipertiroidismo]'],
            ['clave' => 'J44', 'descripcion' => 'Otras enfermedades pulmonares obstructivas crónicas'],
            ['clave' => 'G20', 'descripcion' => 'Enfermedad de Parkinson'],
            ['clave' => 'J45', 'descripcion' => 'Asma'],
            ['clave' => 'F32', 'descripcion' => 'Episodio depresivo'],
            ['clave' => 'K74', 'descripcion' => 'Fibrosis y cirrosis del hígado'],
            ['clave' => 'F20', 'descripcion' => 'Esquizofrenia'],
            ['clave' => 'N08.3', 'descripcion' => 'Trastornos glomerulares en diabetes mellitus (E10-E14†)'],
            ['clave' => 'N18', 'descripcion' => 'Insuficiencia renal crónica'],
        ];

        foreach ($pathologies as $pathology) {
            DB::table('pathologies')->insert([
                'clave' => $pathology['clave'],
                'descripcion' => $pathology['descripcion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}