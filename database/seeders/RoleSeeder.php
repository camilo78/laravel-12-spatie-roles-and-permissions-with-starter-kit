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
            'Admin',
            'User',
            'Manager',
            'Team Lead',
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
            'Admin' => Permission::all(),
            'User' => Permission::where('name', '=', 'roles.index')->orWhere('name', '=', 'products.index')->orWhere('name', '=', 'products.create')->get(),
            'Manager' => Permission::where('name', '=', 'roles.index')->orWhere('name', '=', 'products.index')->orWhere('name', '=', 'products.create')->orWhere('name', '=', 'products.edit')->orWhere('name', '=', 'products.delete')->get(),
            'Team Lead' => Permission::where('name', '=', 'roles.index')->orWhere('name', '=', 'roles.create')->orWhere('name', '=', 'products.index')->orWhere('name', '=', 'products.create')->orWhere('name', '=', 'products.edit')->orWhere('name', '=', 'products.delete')->get(),
        };
    }
}
