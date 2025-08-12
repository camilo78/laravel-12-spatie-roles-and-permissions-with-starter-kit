<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

/**
 * Componente Livewire para mostrar el listado de roles
 * 
 * Este componente maneja la visualización y eliminación de roles
 * del sistema con sus permisos asociados
 * 
 * @package App\Livewire\Roles
 */
class RoleIndex extends Component
{
    /**
     * Renderiza la vista del componente con el listado de roles
     * 
     * Carga todos los roles con sus permisos asociados ordenados
     * por fecha de creación descendente
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.roles.role-index', [
            // Cargar roles con sus permisos para evitar consultas N+1
            'roles' => Role::with('permissions')->latest()->get(),
        ]);
    }

    /**
     * Elimina un rol del sistema
     * 
     * Verifica que el rol no sea administrador y no esté siendo usado
     * por ningún usuario antes de eliminarlo
     * 
     * @param Role $role Rol a eliminar
     * @return void
     */
    public function deleteRole(Role $role): void
    {
        // Verificar si es el rol administrador
        if (strtolower($role->name) === 'administrador' || strtolower($role->name) === 'administrator') {
            session()->flash('error', 'No se puede eliminar el rol administrador.');
            return;
        }

        // Verificar si el rol está siendo usado por algún usuario
        if ($role->users()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un rol que está siendo usado por usuarios.');
            return;
        }

        // Eliminar el rol (los permisos se desvinculan automáticamente)
        $role->delete();

        // Mostrar mensaje de éxito
        session()->flash('success', 'Rol eliminado exitosamente.');
    }
}
