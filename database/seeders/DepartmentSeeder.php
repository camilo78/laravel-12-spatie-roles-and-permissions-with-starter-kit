<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Department;


class DepartmentSeeder extends Seeder {
    public function run(): void {
        $departments = [
            'Atlántida',
            'Colón',
            'Comayagua',
            'Copán',
            'Cortés',
            'Choluteca',
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
