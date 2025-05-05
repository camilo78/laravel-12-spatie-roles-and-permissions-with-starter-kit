<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'users.index',
            'users.create',
            'users.edit',
            'users.delete',
            'products.index',
            'products.create',
            'products.edit',
            'products.delete',
            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);

            unset($permission);
        }
    }
}
