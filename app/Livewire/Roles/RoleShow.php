<?php

namespace App\Livewire\Roles;

use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

/**
 * Componente Livewire para mostrar los detalles de un rol
 * 
 * Este componente maneja la visualización detallada de un rol
 * incluyendo sus permisos asociados
 * 
 * @package App\Livewire\Roles
 */
class RoleShow extends Component
{
    /**
     * Instancia del rol a mostrar
     * 
     * @var Role
     */
    public $role;

    /**
     * Inicializa el componente con el rol a mostrar
     * 
     * @param Role $role Rol a mostrar
     * @return void
     */
    public function mount(Role $role): void
    {
        // Asignar el rol a la propiedad del componente
        $this->role = $role;
    }

    /**
     * Renderiza la vista del componente
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.roles.role-show');
    }
}
