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
        $this->user();
    }

    protected function admin(): void
    {
        $admin = User::factory()->create([
            'name' => 'Camilo Gabriel Alvarado Ramírez',
            'email' => 'camilo.alvarado0501@gmail.com',
            'gender' => 'Masculino',
            'password' => bcrypt('milogaqw12'),
        ]);

        $admin->assignRole('Admin');
    }

    protected function user(): void
    {
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'gender' => 'Masculino',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('User');
    }


}
