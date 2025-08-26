<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pathology;
use App\Models\PatientPathology;
use Illuminate\Database\Seeder;

class PatientPathologySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $pathologies = Pathology::all();

        foreach ($users as $user) {
            $randomPathologies = $pathologies->random(min(2, $pathologies->count()));
            
            foreach ($randomPathologies as $pathology) {
                PatientPathology::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'pathology_id' => $pathology->id,
                    ],
                    [
                        'diagnosed_at' => fake()->dateTimeBetween('-2 years', 'now'),
                        //'status' => fake()->randomElement(['active', 'inactive', 'controlled']),
                        'status' => fake()->randomElement(['active']),
                    ]
                );
            }
        }
    }
}