<?php

namespace App\Livewire\Users;

use App\Livewire\BaseIndexComponent;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Componente Livewire para el índice de usuarios
 * Extiende BaseIndexComponent para funcionalidad estandarizada
 */
class UserIndex extends BaseIndexComponent
{
    // Listeners para eventos específicos de usuarios
    protected $listeners = ['refreshUsers' => '$refresh'];

    /**
     * Define los campos donde se puede buscar
     * 
     * @return array Campos de búsqueda con sus tipos
     */
    protected function getSearchableFields(): array
    {
        return [
            'name' => 'like',
            'dni' => 'like',
            'email' => 'like',
            'phone' => 'like'
        ];
    }

    /**
     * Define los campos permitidos para ordenamiento
     * 
     * @return array Campos ordenables
     */
    protected function getSortableFields(): array
    {
        return ['id', 'name', 'dni', 'email', 'created_at', 'status'];
    }

    /**
     * Obtiene la clase del modelo User
     * 
     * @return string Clase del modelo
     */
    protected function getModelClass(): string
    {
        return User::class;
    }

    /**
     * Define las relaciones a cargar con eager loading
     * 
     * @return array Relaciones a cargar
     */
    protected function getEagerLoadRelations(): array
    {
        return ['roles', 'department', 'municipality', 'locality'];
    }

    /**
     * Renderiza el componente con la lista de usuarios
     */
    public function render()
    {
        $users = $this->buildQuery();
        return view('livewire.users.user-index', compact('users'));
    }

    /**
     * Cambia el estado activo/inactivo de un usuario
     * 
     * @param User $user Usuario a modificar
     */
    public function toggleStatus(User $user): void
    {
        try {
            $user->update(['status' => !$user->status]);
            $status = $user->status ? 'activado' : 'desactivado';
            
            session()->flash('success', "Usuario {$status} exitosamente.");
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de usuario: ' . $e->getMessage(), ['user_id' => $user->id]);
            session()->flash('error', 'Error al cambiar el estado del usuario.');
        }
    }

    /**
     * Verifica si un usuario puede ser eliminado
     * 
     * @param User $user Usuario a verificar
     * @return bool True si puede ser eliminado
     */
    protected function canDelete($user): bool
    {
        return !($user->hasRole('administrador') || $user->hasRole('administrator'));
    }

    /**
     * Elimina un usuario usando el método base con validaciones específicas
     * 
     * @param int $userId ID del usuario a eliminar
     */
    public function deleteUser($userId): void
    {
        $this->delete($userId, 'Usuario eliminado exitosamente.');
    }
}

