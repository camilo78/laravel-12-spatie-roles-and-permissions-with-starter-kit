<?php

namespace Database\Seeders;

use App\Models\SystemConfiguration;
use Illuminate\Database\Seeder;

class SystemConfigurationSeeder extends Seeder
{
    public function run(): void
    {
        SystemConfiguration::create([
            'hospital_name' => 'Hospital General Atlántida',
            'program_name' => 'Programa de Entrega de Medicamentos en Casa',
            'program_manager' => 'Lic. Sandra Patricia Nuñez Hernández',
            'first_delivery_days' => 30,
            'subsequent_delivery_days' => 120,
            'app_logo' => 'img/appLogo.png',
            'hospital_logo' => 'img/hospitalLogo.png',
        ]);
    }
}