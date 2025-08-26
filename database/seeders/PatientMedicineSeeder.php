<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medicine;
use App\Models\PatientMedicine;
use Illuminate\Database\Seeder;

class PatientMedicineSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $medicines = Medicine::all();

        foreach ($users as $user) {
            $randomMedicines = $medicines->random(min(3, $medicines->count()));
            
            foreach ($randomMedicines as $medicine) {
                PatientMedicine::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'medicine_id' => $medicine->id,
                    ],
                    [
                        'dosage' => fake()->randomElement(['1 tablet', '2 tablets', '5ml', '10ml', '1 capsule']),
                        'quantity' => fake()->numberBetween(1, 30),
                        'start_date' => fake()->dateTimeBetween('-1 year', 'now'),
                        'end_date' => fake()->optional()->dateTimeBetween('now', '+6 months'),
                       // 'status' => fake()->randomElement(['active', 'suspended', 'completed']),
                        'status' => fake()->randomElement(['active']),
                    ]
                );
            }
        }
    }
}