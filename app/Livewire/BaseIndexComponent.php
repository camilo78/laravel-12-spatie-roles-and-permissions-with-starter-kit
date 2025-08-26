<?php

namespace App\Livewire;

use App\Traits\HasSearchableQueries;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Componente base abstracto para índices estandarizados
 * Proporciona funcionalidad común para listados con búsqueda, ordenamiento y paginación
 */
abstract class BaseIndexComponent extends Component
{
    use WithPagination, HasSearchableQueries;

    /**
     * Define los campos donde se puede buscar
     * Formato: ['campo' => 'tipo']
     * Tipos: 'like', 'exact', 'relation'
     * 
     * @return array
     */
    abstract protected function getSearchableFields(): array;

    /**
     * Define los campos permitidos para ordenamiento
     * 
     * @return array
     */
    abstract protected function getSortableFields(): array;

    /**
     * Obtiene el modelo base para las consultas
     * 
     * @return string Nombre de la clase del modelo
     */
    abstract protected function getModelClass(): string;

    /**
     * Define las relaciones que se deben cargar con eager loading
     * 
     * @return array
     */
    protected function getEagerLoadRelations(): array
    {
        return [];
    }

    /**
     * Aplica filtros adicionales específicos del componente
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyAdditionalFilters($query)
    {
        return $query;
    }

    /**
     * Construye la consulta principal del índice
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function buildQuery()
    {
        $modelClass = $this->getModelClass();
        
        // Iniciar consulta base
        $query = $modelClass::query();

        // Aplicar eager loading si está definido
        $relations = $this->getEagerLoadRelations();
        if (!empty($relations)) {
            $query->with($relations);
        }

        // Aplicar búsqueda
        $query = $this->applySearch($query, $this->getSearchableFields());

        // Aplicar filtros adicionales específicos del componente
        $query = $this->applyAdditionalFilters($query);

        // Aplicar ordenamiento
        $query = $this->applySorting($query, $this->getSortableFields());

        // Ejecutar consulta paginada
        return $this->executePaginatedQuery($query);
    }

    /**
     * Elimina un registro de forma segura
     * 
     * @param int $id ID del registro a eliminar
     * @param string $successMessage Mensaje de éxito personalizado
     */
    public function delete($id, $successMessage = 'Registro eliminado exitosamente.')
    {
        try {
            $modelClass = $this->getModelClass();
            $record = $modelClass::findOrFail($id);
            
            // Verificar permisos adicionales si es necesario
            if (method_exists($this, 'canDelete') && !$this->canDelete($record)) {
                session()->flash('error', 'No tienes permisos para eliminar este registro.');
                return;
            }

            $record->delete();
            session()->flash('success', $successMessage);
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar registro: ' . $e->getMessage(), [
                'id' => $id,
                'component' => get_class($this)
            ]);
            session()->flash('error', 'Error al eliminar el registro.');
        }
    }

    /**
     * Método render que debe ser implementado por cada componente
     */
    abstract public function render();
}