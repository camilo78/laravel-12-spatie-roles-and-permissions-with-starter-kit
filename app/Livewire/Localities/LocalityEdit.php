<?php

namespace App\Livewire\Localities;

use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Componente Livewire para editar localidades existentes
 * 
 * Este componente maneja la edición de localidades con selección
 * jerárquica de departamento y municipio
 * 
 * @package App\Livewire\Localities
 */
class LocalityEdit extends Component
{
    /**
     * Instancia de la localidad a editar
     * 
     * @var Locality
     */
    public Locality $locality;

    /**
     * Nombre de la localidad
     * 
     * @var string
     */
    public $name = '';

    /**
     * ID del departamento seleccionado
     * 
     * @var string
     */
    public $selectedDepartment = '';

    /**
     * ID del municipio seleccionado
     * 
     * @var string
     */
    public $selectedMunicipality = '';

    /**
     * Estado de envío del formulario para prevenir doble envío
     * 
     * @var bool
     */
    public $isSubmitting = false;

    /**
     * Reglas de validación del formulario
     * 
     * @var array
     */
    protected $rules = [
        'name' => 'required|string|max:255',
        'selectedMunicipality' => 'required|exists:municipalities,id',
    ];

    /**
     * Inicializa el componente con los datos de la localidad a editar
     * 
     * @param Locality $locality Localidad a editar
     * @return void
     */
    public function mount(Locality $locality): void
    {
        // Asignar la localidad y cargar sus datos
        $this->locality = $locality;
        $this->name = $locality->name;
        $this->selectedMunicipality = $locality->municipality_id;
        $this->selectedDepartment = $locality->municipality->department_id;
    }

    /**
     * Se ejecuta cuando cambia el departamento seleccionado
     * Resetea el municipio si se cambia el departamento
     * 
     * @return void
     */
    public function updatedSelectedDepartment(): void
    {
        // Si se cambia el departamento, limpiar la selección de municipio
        if ($this->selectedDepartment != $this->locality->municipality->department_id) {
            $this->selectedMunicipality = '';
        }
    }

    /**
     * Actualiza la localidad en la base de datos
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function update()
    {
        // Prevenir doble envío
        if ($this->isSubmitting) return;
        
        $this->isSubmitting = true;
        
        try {
            // Validar los datos del formulario
            $this->validate();

            // Actualizar la localidad
            $this->locality->update([
                'name' => $this->name,
                'municipality_id' => $this->selectedMunicipality,
            ]);

            // Mostrar mensaje de éxito y redireccionar
            session()->flash('success', 'Localidad actualizada exitosamente.');
            return redirect()->route('localities.index', [
                'department_id' => $this->selectedDepartment,
                'municipality_id' => $this->selectedMunicipality
            ]);
        } catch (\Exception $e) {
            // Resetear estado de envío en caso de error
            $this->isSubmitting = false;
            throw $e;
        }
    }

    /**
     * Renderiza la vista del componente
     * 
     * @return View
     */
    public function render(): View
    {
        // Cargar todos los departamentos ordenados por nombre
        $departments = Department::orderBy('name')->get();
        
        // Cargar municipios del departamento seleccionado o colección vacía
        $municipalities = $this->selectedDepartment 
            ? Municipality::where('department_id', $this->selectedDepartment)->orderBy('name')->get()
            : collect();

        return view('livewire.localities.locality-edit', [
            'departments' => $departments,
            'municipalities' => $municipalities,
        ]);
    }
}