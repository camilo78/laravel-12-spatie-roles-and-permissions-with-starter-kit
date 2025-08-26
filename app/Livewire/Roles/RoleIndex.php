<?php

namespace App\Livewire\Roles;

use App\Livewire\BaseIndexComponent;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

/**
 * Componente para el índice de roles
 * Extiende BaseIndexComponent para funcionalidad estandarizada
 */
class RoleIndex extends BaseIndexComponent
{
    /**
     * Define los campos donde se puede buscar
     */
    protected function getSearchableFields(): array
    {
        return [
            'name' => 'like',
            'guard_name' => 'like'
        ];
    }

    /**
     * Define los campos permitidos para ordenamiento
     */
    protected function getSortableFields(): array
    {
        return ['id', 'name', 'guard_name', 'created_at'];
    }

    /**
     * Obtiene la clase del modelo Role
     */
    protected function getModelClass(): string
    {
        return Role::class;
    }

    /**
     * Define las relaciones a cargar con eager loading
     */
    protected function getEagerLoadRelations(): array
    {
        return ['permissions'];
    }

    /**
     * Renderiza el componente con la lista de roles
     */
    public function render(): View
    {
        $roles = $this->buildQuery();
        return view('livewire.roles.role-index', compact('roles'));
    }

    /**
     * Verifica si un rol puede ser eliminado
     */
    protected function canDelete($role): bool
    {
        $isAdmin = strtolower($role->name) === 'administrador' || strtolower($role->name) === 'administrator';
        $hasUsers = $role->users()->count() > 0;
        
        if ($isAdmin) {
            session()->flash('error', 'No se puede eliminar el rol administrador.');
            return false;
        }
        
        if ($hasUsers) {
            session()->flash('error', 'No se puede eliminar un rol que está siendo usado por usuarios.');
            return false;
        }
        
        return true;
    }

    /**
     * Elimina un rol
     */
    public function deleteRole($roleId)
    {
        $this->delete($roleId, 'Rol eliminado exitosamente.');
    }
}
