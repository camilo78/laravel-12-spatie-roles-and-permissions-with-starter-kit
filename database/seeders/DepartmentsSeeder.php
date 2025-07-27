<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder {
    public function run(): void {
        $departments = [
            'Atlántida',
            'Choluteca',
            'Colón',
            'Comayagua',
            'Copán',
            'Cortés',
            'El Paraíso',
            'Francisco Morazán',
            'Gracias a Dios',
            'Intibucá',
            'Islas de la Bahía',
            'La Paz',
            'Lempira',
            'Ocotepeque',
            'Olancho',
            'Santa Bárbara',
            'Valle',
            'Yoro',
        ];

        foreach ($departments as $index => $name) {
            DB::table('departments')->insert([
                'code' => $index + 1,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}