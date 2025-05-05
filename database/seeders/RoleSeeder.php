<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'User',
        ];

        foreach ($roles as $role) {
            $createRole = Role::create(['name' => $role]);

            $createRole->syncPermissions($this->getPermission($role));

            unset($role);
        }
    }

    protected function getPermission($role): Collection
    {
        return match ($role) {
            'Super Admin' => Permission::all(),
            'Admin' => Permission::where([
                ['name', '!=', 'users.delete'],
                ['name', '!=', 'products.delete'],
                ['name', '!=', 'roles.delete'],
            ])->get(),
            'User' => Permission::where([
                ['name', '!=', 'users.create'],
                ['name', '!=', 'users.edit'],
                ['name', '!=', 'users.show'],
                ['name', '!=', 'users.delete'],
                ['name', '!=', 'products.create'],
                ['name', '!=', 'products.edit'],
                ['name', '!=', 'products.show'],
                ['name', '!=', 'products.delete'],
                ['name', '!=', 'roles.create'],
                ['name', '!=', 'roles.edit'],
                ['name', '!=', 'roles.show'],
                ['name', '!=', 'roles.delete'],
            ])->get(),
        };
    }
}
