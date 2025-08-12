<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Componente Livewire para crear nuevos roles
 * 
 * Este componente maneja la creación de roles con sus permisos asociados
 * utilizando el paquete Spatie Laravel Permission
 * 
 * @package App\Livewire\Roles
 */
class RoleCreate extends Component
{
    /**
     * Nombre del rol a crear
     * 
     * @var string
     */
    public $name;

    /**
     * Array de permisos seleccionados para el rol
     * 
     * @var array
     */
    public $permissions = [];

    /**
     * Todos los permisos disponibles en el sistema
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $allPermissions = [];

    /**
     * Inicializa el componente cargando todos los permisos disponibles
     * 
     * @return void
     */
    public function mount(): void
    {
        // Cargar todos los permisos ordenados por fecha de creación descendente
        $this->allPermissions = Permission::latest()->get();
    }

    /**
     * Renderiza la vista del componente
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.roles.role-create');
    }

    /**
     * Crea un nuevo rol con los permisos seleccionados
     * 
     * Valida los datos del formulario, crea el rol y sincroniza
     * los permisos seleccionados
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createRole()
    {
        // Validar los datos del formulario
        $this->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        // Crear el nuevo rol
        $role = Role::create([
            'name' => $this->name,
        ]);

        // Sincronizar los permisos seleccionados con el rol
        $role->syncPermissions($this->permissions);

        // Redireccionar al índice con mensaje de éxito
        return to_route('roles.index')->with('success', 'Role created successfully.');
    }
}
