<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->admin();
   //     $this->user();

      /*   User::factory(198)->create()->each(function ($user) {
            $user->assignRole('User');
        }); */
    }

    protected function admin(): void
    {
        $admin = User::factory()->create([
            'name' => 'Camilo Gabriel Alvarado Ramírez',
            'email' => 'camilo.alvarado0501@gmail.com',
            'dni' => '0501197809263',
            'phone' => '96645637 87886036 96585441',
            'department_id' => 1, // Atlántida
            'municipality_id' => 1, // La Ceiba
            'locality_id' => 1, // Locality ID for La Ceiba
            'address' => '123 Main St, San Salvador',
            'gender' => 'Masculino',
            'status' => true,
            'admission_date' => '2024-01-15',
            'password' => bcrypt('milogaqw12'),
        ]);

        $admin->assignRole('Admin');
    }

 /*    protected function user(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe Smith',
            'email' => 'user@example.com',
            'dni' => '1234567890123',
            'phone' => '555-1234',
            'department_id' => 1, // Atlántida
            'municipality_id' => 1, // La Ceiba
            'locality_id' => 2, // Locality ID for La Ceiba
            'address' => '456 Elm St, San Salvador',
            'gender' => 'Masculino',
            'status' => true,
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('User');
    } */

}
