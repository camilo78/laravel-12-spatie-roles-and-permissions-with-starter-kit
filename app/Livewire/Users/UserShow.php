<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Componente para mostrar el perfil completo de un usuario.
 * Muestra información personal, patologías y medicamentos asignados.
 */
class UserShow extends Component
{
    /** @var User Usuario a mostrar */
    public User $user;

    /**
     * Inicializa el componente con el usuario específico.
     * 
     * @param User $user Usuario inyectado por route model binding
     */
    public function mount(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Renderiza la vista del perfil del usuario.
     */
    public function render(): View
    {
        return view('livewire.users.user-show');
    }
}
