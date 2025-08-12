<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Componente Livewire para editar roles existentes
 * 
 * Este componente maneja la edición de roles y la actualización
 * de sus permisos asociados
 * 
 * @package App\Livewire\Roles
 */
class RoleEdit extends Component
{
    /**
     * Nombre del rol
     * 
     * @var string
     */
    public $name;

    /**
     * Instancia del rol a editar
     * 
     * @var Role
     */
    public $role;

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
     * Inicializa el componente con los datos del rol a editar
     * 
     * @param Role $role Rol a editar
     * @return void
     */
    public function mount(Role $role): void
    {
        // Asignar el rol a la propiedad del componente
        $this->role = $role;
        
        // Cargar todos los permisos disponibles
        $this->allPermissions = Permission::latest()->get();
        
        // Cargar los datos actuales del rol
        $this->name = $this->role->name;
        $this->permissions = $this->role->permissions->pluck('name')->toArray();
    }

    /**
     * Renderiza la vista del componente
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.roles.role-edit');
    }

    /**
     * Actualiza el rol con los nuevos datos y permisos
     * 
     * Valida los datos del formulario, actualiza el rol y sincroniza
     * los permisos seleccionados
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editRole()
    {
        // Validar los datos del formulario (excluir el rol actual de la validación unique)
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role->id,
            'permissions' => 'required',
        ]);

        // Actualizar el nombre del rol
        $this->role->name = $this->name;
        $this->role->save();

        // Sincronizar los permisos seleccionados con el rol
        $this->role->syncPermissions($this->permissions);

        // Redireccionar al índice con mensaje de éxito
        return to_route('roles.index')->with('success', 'Role updated successfully.');
    }
}
