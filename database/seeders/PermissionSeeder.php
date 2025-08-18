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
            // Usuarios
            'users.index',
            'users.create',
            'users.edit',
            'users.delete',
            'users.show',
            'users.import',
            'users.export',
            
            // Roles
            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.delete',
            
            // Medicamentos
            'medicines.index',
            'medicines.create',
            'medicines.edit',
            'medicines.delete',
            'medicines.show',
            
            // Patologías
            'pathologies.index',
            'pathologies.create',
            'pathologies.edit',
            'pathologies.delete',
            'pathologies.show',
            
            // Departamentos
            'departments.index',
            'departments.create',
            'departments.edit',
            'departments.delete',
            'departments.show',
            
            // Municipios
            'municipalities.index',
            'municipalities.create',
            'municipalities.edit',
            'municipalities.delete',
            'municipalities.show',
            
            // Localidades
            'localities.index',
            'localities.create',
            'localities.edit',
            'localities.delete',
            'localities.show',
            
            // Medicamentos de Usuario
            'user-medicines.index',
            'user-medicines.create',
            'user-medicines.edit',
            'user-medicines.delete',
            
            // Patologías de Usuario
            'user-pathologies.index',
            'user-pathologies.create',
            'user-pathologies.edit',
            'user-pathologies.delete',
            
            // Entregas
            'deliveries.index',
            'deliveries.create',
            'deliveries.edit',
            'deliveries.delete',
            'deliveries.show',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);

            unset($permission);
        }
    }
}
