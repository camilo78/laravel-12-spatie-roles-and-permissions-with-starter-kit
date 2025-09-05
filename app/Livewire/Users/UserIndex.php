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
    public $startDate = '';
    public $endDate = '';
    
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
     * Aplica filtros adicionales a la consulta
     */
    protected function applyAdditionalFilters($query)
    {
        if ($this->startDate) {
            $query->whereDate('admission_date', '>=', $this->startDate);
        }
        
        if ($this->endDate) {
            $query->whereDate('admission_date', '<=', $this->endDate);
        }
        
        return $query;
    }

    /**
     * Renderiza el componente con la lista de usuarios
     */
    public function render()
    {
        $users = $this->buildQuery();
        return view('livewire.users.user-index', compact('users'));
    }

    public function resetFilters()
    {
        $this->startDate = '';
        $this->endDate = '';
        $this->search = '';
    }

    public function exportAll()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\UsersExport(), 
            'usuarios_todos.xlsx'
        );
    }

    public function exportTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\UsersTemplateExport(), 
            'plantilla_usuarios.xlsx'
        );
    }

    public function exportFiltered()
    {
        $modelClass = $this->getModelClass();
        $query = $modelClass::query()->with($this->getEagerLoadRelations());
        
        // Aplicar búsqueda
        $query = $this->applySearch($query, $this->getSearchableFields());
        
        // Aplicar filtros de fecha
        $query = $this->applyAdditionalFilters($query);
        
        $users = $query->get();
        
        $filename = 'usuarios_filtrados';
        if ($this->startDate || $this->endDate) {
            $filename .= '_' . ($this->startDate ?: 'inicio') . '_' . ($this->endDate ?: 'fin');
        }
        $filename .= '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\UsersExport($users), 
            $filename
        );
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

