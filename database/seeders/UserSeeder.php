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
        $this->manager();
        $this->teamLead();
    }

    protected function admin(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('Admin');
    }

    protected function user(): void
    {
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('User');
    }

    protected function manager(): void
    {
        $manager = User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);

        $manager->assignRole('Manager');
    }

    protected function teamLead(): void
    {
        $teamLead = User::factory()->create([
            'name' => 'Team Lead',
            'email' => 'team-lead@example.com',
            'password' => bcrypt('password'),
        ]);

        $teamLead->assignRole('Team Lead');
    }
}
