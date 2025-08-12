<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

/**
 * Componente Livewire para el índice de usuarios
 * 
 * Gestiona la lista de usuarios con funcionalidades de búsqueda,
 * paginación, cambio de estado y eliminación.
 */
class UserIndex extends Component
{
    use WithPagination;

    // Propiedades públicas
    public string $search = '';
    public int $perPage = 10;
    
    // Configuración de paginación
    protected $paginationTheme = 'tailwind';
    
    // Listeners para eventos
    protected $listeners = ['refreshUsers' => '$refresh'];

    /**
     * Se ejecuta cuando cambia el valor de búsqueda
     * Reinicia la paginación para mostrar resultados desde la primera página
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Renderiza el componente con la lista de usuarios filtrada
     */
    public function render()
    {
        $searchTerm = trim($this->search);
        
        $users = User::query()
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', "%{$searchTerm}%")
                             ->orWhere('dni', 'like', "%{$searchTerm}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage)
            ->onEachSide(1);

        return view('livewire.users.user-index', compact('users'));
    }

    /**
     * Cambia el estado activo/inactivo de un usuario
     */
    public function toggleStatus(User $user): void
    {
        try {
            $user->update(['status' => !$user->status]);
            $status = $user->status ? 'activado' : 'desactivado';
            
            session()->flash('success', "Usuario {$status} exitosamente.");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado del usuario.');
        }
    }

    /**
     * Elimina un usuario del sistema
     * 
     * Verifica que el usuario no tenga rol de administrador
     * antes de proceder con la eliminación
     */
    public function deleteUser(User $user): void
    {
        try {
            // Verificar si el usuario tiene rol de administrador
            if ($user->hasRole('administrador') || $user->hasRole('administrator')) {
                session()->flash('error', 'No se puede eliminar un usuario con rol de administrador.');
                return;
            }

            $userName = $user->name;
            $user->delete();
            
            session()->flash('success', "Usuario '{$userName}' eliminado exitosamente.");
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el usuario. Verifique que no tenga datos relacionados.');
        }
    }
}

