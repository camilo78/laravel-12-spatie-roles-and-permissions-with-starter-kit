<?php

namespace App\Livewire\Localities;

use App\Livewire\BaseIndexComponent;
use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

/**
 * Componente Livewire para mostrar el listado de localidades
 * 
 * Este componente maneja la visualización, filtrado y eliminación
 * de localidades con selección jerárquica y paginación
 * 
 * @package App\Livewire\Localities
 */
class LocalityIndex extends BaseIndexComponent
{

    /**
     * ID del departamento seleccionado para filtrar
     * 
     * @var string
     */
    public $selectedDepartment = '';

    /**
     * ID del municipio seleccionado para filtrar
     * 
     * @var string
     */
    public $selectedMunicipality = '';



    /**
     * Inicializa el componente con valores de la URL
     * 
     * @return void
     */
    public function mount(): void
    {
        // Obtener valores de departamento y municipio de la URL si existen
        $this->selectedDepartment = request('department_id', '');
        $this->selectedMunicipality = request('municipality_id', '');
    }

    /**
     * Se ejecuta cuando cambia el departamento seleccionado
     * Resetea el municipio y la paginación
     * 
     * @return void
     */
    public function updatedSelectedDepartment(): void
    {
        // Limpiar selección de municipio y resetear paginación
        $this->selectedMunicipality = '';
        $this->resetPage();
    }

    /**
     * Se ejecuta cuando cambia el municipio seleccionado
     * Resetea la paginación
     * 
     * @return void
     */
    public function updatedSelectedMunicipality(): void
    {
        // Resetear paginación al cambiar municipio
        $this->resetPage();
    }



    /**
     * Define los campos donde se puede buscar
     * 
     * @return array Campos de búsqueda con sus tipos
     */
    protected function getSearchableFields(): array
    {
        return [
            'name' => 'like'
        ];
    }

    /**
     * Define los campos permitidos para ordenamiento
     * 
     * @return array Campos ordenables
     */
    protected function getSortableFields(): array
    {
        return ['id', 'name', 'created_at'];
    }

    /**
     * Obtiene la clase del modelo Locality
     * 
     * @return string Clase del modelo
     */
    protected function getModelClass(): string
    {
        return Locality::class;
    }

    /**
     * Define las relaciones a cargar con eager loading
     * 
     * @return array Relaciones a cargar
     */
    protected function getEagerLoadRelations(): array
    {
        return ['municipality.department'];
    }

    /**
     * Aplica filtros adicionales específicos de localidades
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyAdditionalFilters($query)
    {
        if (empty($this->selectedMunicipality)) {
            return $query->whereRaw('1 = 0');
        }
        
        return $query->where('municipality_id', $this->selectedMunicipality);
    }

    /**
     * Elimina una localidad usando el método base
     * 
     * @param int $localityId ID de la localidad a eliminar
     */
    public function deleteLocality($localityId)
    {
        $this->delete($localityId, 'Localidad eliminada exitosamente.');
    }

    /**
     * Renderiza la vista del componente con datos filtrados
     * 
     * @return View
     */
    public function render(): View
    {
        // Cargar todos los departamentos
        $departments = Department::orderBy('name')->get();
        
        // Cargar municipios del departamento seleccionado o colección vacía
        $municipalities = $this->selectedDepartment 
            ? Municipality::where('department_id', $this->selectedDepartment)->orderBy('name')->get()
            : collect();

        // Cargar localidades usando el método estandarizado
        $localities = $this->selectedMunicipality ? $this->buildQuery() : collect();

        return view('livewire.localities.locality-index', [
            'departments' => $departments,
            'municipalities' => $municipalities,
            'localities' => $localities,
        ]);
    }
}