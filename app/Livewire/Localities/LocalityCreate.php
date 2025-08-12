<?php

namespace App\Livewire\Localities;

use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Componente Livewire para crear nuevas localidades
 * 
 * Este componente maneja la creación de localidades con selección
 * jerárquica de departamento y municipio
 * 
 * @package App\Livewire\Localities
 */
class LocalityCreate extends Component
{
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
     * Resetea el municipio si se cambia el departamento
     * 
     * @return void
     */
    public function updatedSelectedDepartment(): void
    {
        // Si se cambia el departamento, limpiar la selección de municipio
        if ($this->selectedDepartment != request('department_id')) {
            $this->selectedMunicipality = '';
        }
    }

    /**
     * Guarda la nueva localidad en la base de datos
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function save()
    {
        // Prevenir doble envío
        if ($this->isSubmitting) return;
        
        $this->isSubmitting = true;
        
        try {
            // Validar los datos del formulario
            $this->validate();

            // Crear la nueva localidad
            Locality::create([
                'name' => $this->name,
                'municipality_id' => $this->selectedMunicipality,
            ]);

            // Mostrar mensaje de éxito y redireccionar
            session()->flash('success', 'Localidad creada exitosamente.');
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

        return view('livewire.localities.locality-create', [
            'departments' => $departments,
            'municipalities' => $municipalities,
        ]);
    }
}