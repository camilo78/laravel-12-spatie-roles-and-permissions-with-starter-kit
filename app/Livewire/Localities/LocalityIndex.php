<?php

namespace App\Livewire\Localities;

use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Componente Livewire para mostrar el listado de localidades
 * 
 * Este componente maneja la visualización, filtrado y eliminación
 * de localidades con selección jerárquica y paginación
 * 
 * @package App\Livewire\Localities
 */
class LocalityIndex extends Component
{
    use WithPagination;

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
     * Término de búsqueda para filtrar localidades
     * 
     * @var string
     */
    public $search = '';

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
     * Se ejecuta cuando cambia el término de búsqueda
     * Resetea la paginación
     * 
     * @return void
     */
    public function updatedSearch(): void
    {
        // Resetear paginación al cambiar búsqueda
        $this->resetPage();
    }

    /**
     * Elimina una localidad del sistema
     * 
     * @param int $localityId ID de la localidad a eliminar
     * @return void
     */
    public function deleteLocality($localityId): void
    {
        // Buscar y eliminar la localidad si existe
        $locality = Locality::find($localityId);
        if ($locality) {
            $locality->delete();
            session()->flash('success', 'Localidad eliminada exitosamente.');
        }
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

        // Cargar localidades solo si hay municipio seleccionado
        $localities = collect();
        if ($this->selectedMunicipality) {
            $localities = Locality::with('municipality')
                ->where('municipality_id', $this->selectedMunicipality)
                ->when($this->search, function ($query) {
                    // Filtrar por nombre si hay término de búsqueda
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->paginate(10);
        }

        return view('livewire.localities.locality-index', [
            'departments' => $departments,
            'municipalities' => $municipalities,
            'localities' => $localities,
        ]);
    }
}