<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Componente Livewire para mostrar detalles de usuario
 * 
 * Gestiona la visualización de información completa de un usuario específico,
 * incluyendo datos personales, ubicación geográfica y roles asignados.
 */
class UserShow extends Component
{
    // Usuario a mostrar
    public User $user;

    /**
     * Inicializa el componente con el usuario específico
     * 
     * @param User $user Usuario a mostrar (inyectado por route model binding)
     */
    public function mount(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Renderiza la vista del componente
     * 
     * @return View Vista con los detalles del usuario
     */
    public function render(): View
    {
        return view('livewire.users.user-show');
    }
}
