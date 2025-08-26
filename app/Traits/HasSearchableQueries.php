<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

/**
 * Trait para estandarizar funcionalidades de búsqueda en componentes Livewire
 * Proporciona métodos seguros y optimizados para filtrado y consultas
 */
trait HasSearchableQueries
{
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'desc';

    /**
     * Reinicia la paginación cuando cambia el término de búsqueda
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reinicia la paginación cuando cambia el número de elementos por página
     */
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Cambia la dirección del ordenamiento para un campo específico
     * 
     * @param string $field Campo por el cual ordenar
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    /**
     * Sanitiza el término de búsqueda para evitar inyecciones y errores
     * 
     * @param string $search Término de búsqueda sin procesar
     * @return string Término sanitizado
     */
    protected function sanitizeSearch($search)
    {
        if (empty($search)) {
            return '';
        }

        // Eliminar caracteres especiales peligrosos y espacios extra
        $sanitized = trim(preg_replace('/[^\p{L}\p{N}\s\-_.@]/u', '', $search));
        
        // Limitar longitud para evitar consultas muy largas
        return substr($sanitized, 0, 100);
    }

    /**
     * Aplica filtros de búsqueda de forma segura a una consulta
     * 
     * @param Builder $query Consulta base
     * @param array $searchableFields Campos donde buscar ['field' => 'type']
     * @param string $searchTerm Término de búsqueda (opcional, usa $this->search por defecto)
     * @return Builder Consulta con filtros aplicados
     */
    protected function applySearch(Builder $query, array $searchableFields, $searchTerm = null)
    {
        $searchTerm = $searchTerm ?? $this->search;
        $sanitizedSearch = $this->sanitizeSearch($searchTerm);

        if (empty($sanitizedSearch)) {
            return $query;
        }

        try {
            return $query->where(function ($q) use ($searchableFields, $sanitizedSearch) {
                foreach ($searchableFields as $field => $type) {
                    switch ($type) {
                        case 'like':
                            $q->orWhere($field, 'LIKE', "%{$sanitizedSearch}%");
                            break;
                        case 'exact':
                            $q->orWhere($field, $sanitizedSearch);
                            break;
                        case 'relation':
                            // Para búsquedas en relaciones: 'user.name' => 'relation'
                            $parts = explode('.', $field);
                            if (count($parts) === 2) {
                                $q->orWhereHas($parts[0], function ($subQuery) use ($parts, $sanitizedSearch) {
                                    $subQuery->where($parts[1], 'LIKE', "%{$sanitizedSearch}%");
                                });
                            }
                            break;
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error('Error en búsqueda: ' . $e->getMessage(), [
                'search_term' => $sanitizedSearch,
                'fields' => $searchableFields,
                'component' => get_class($this)
            ]);
            return $query; // Retorna consulta sin filtros en caso de error
        }
    }

    /**
     * Aplica ordenamiento de forma segura
     * 
     * @param Builder $query Consulta base
     * @param array $sortableFields Campos permitidos para ordenamiento
     * @return Builder Consulta con ordenamiento aplicado
     */
    protected function applySorting(Builder $query, array $sortableFields = [])
    {
        // Validar que el campo de ordenamiento esté permitido
        if (!empty($sortableFields) && !in_array($this->sortField, $sortableFields)) {
            $this->sortField = $sortableFields[0] ?? 'id';
        }

        // Validar dirección de ordenamiento
        if (!in_array($this->sortDirection, ['asc', 'desc'])) {
            $this->sortDirection = 'desc';
        }

        try {
            return $query->orderBy($this->sortField, $this->sortDirection);
        } catch (\Exception $e) {
            Log::error('Error en ordenamiento: ' . $e->getMessage(), [
                'sort_field' => $this->sortField,
                'sort_direction' => $this->sortDirection,
                'component' => get_class($this)
            ]);
            return $query->orderBy('id', 'desc'); // Fallback seguro
        }
    }

    /**
     * Valida y ajusta el número de elementos por página
     * 
     * @return int Número válido de elementos por página
     */
    protected function getValidatedPerPage()
    {
        $allowedPerPage = [5, 10, 15, 25, 50];
        
        if (!in_array($this->perPage, $allowedPerPage)) {
            $this->perPage = 10;
        }

        return $this->perPage;
    }

    /**
     * Ejecuta una consulta paginada de forma segura
     * 
     * @param Builder $query Consulta preparada
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function executePaginatedQuery(Builder $query)
    {
        try {
            return $query->paginate($this->getValidatedPerPage());
        } catch (\Exception $e) {
            Log::error('Error en paginación: ' . $e->getMessage(), [
                'per_page' => $this->perPage,
                'component' => get_class($this)
            ]);
            
            // Fallback: consulta simple sin filtros complejos
            return $query->getModel()->newQuery()->paginate(10);
        }
    }
}