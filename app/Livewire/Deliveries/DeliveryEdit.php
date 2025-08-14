<?php

namespace App\Livewire\Deliveries;

use App\Models\MedicineDelivery;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Componente Livewire para editar entregas de medicamentos existentes
 * 
 * Este componente maneja la edición de entregas de medicamentos
 * con validación de permisos de edición
 * 
 * @package App\Livewire\Deliveries
 */
class DeliveryEdit extends Component
{
    /**
     * Instancia de la entrega a editar
     * 
     * @var MedicineDelivery
     */
    public MedicineDelivery $delivery;

    /**
     * Nombre de la entrega
     * 
     * @var string
     */
    public $name = '';

    /**
     * Fecha de inicio de la entrega
     * 
     * @var string
     */
    public $start_date = '';

    /**
     * Fecha de fin de la entrega
     * 
     * @var string
     */
    public $end_date = '';

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
        'start_date' => 'required|date',
        'status' => 'required|string|in:pending,completed,active',
        'end_date' => 'required|date|after:start_date',
    ];

    /**
     * Inicializa el componente con los datos de la entrega a editar
     * 
     * @param MedicineDelivery $delivery Entrega a editar
     * @return void
     */
    public function mount(MedicineDelivery $delivery): void
    {
        // Verificar si la entrega puede ser editada
        if (!$delivery->isEditable()) {
            abort(403, 'Esta entrega no puede ser editada.');
        }

        // Asignar la entrega y cargar sus datos
        $this->delivery = $delivery;
        $this->name = $delivery->name;
        $this->start_date = $delivery->start_date->format('Y-m-d');
        $this->end_date = $delivery->end_date->format('Y-m-d');
    }

    /**
     * Actualiza la entrega con los nuevos datos
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function save()
    {
        // Prevenir doble envío y verificar permisos de edición
        if ($this->isSubmitting || !$this->delivery->isEditable()) return;
        
        $this->isSubmitting = true;
        
        try {
            // Validar los datos del formulario
            $this->validate();

            // Actualizar la entrega
            $this->delivery->update([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            // Mostrar mensaje de éxito y redireccionar
            session()->flash('success', 'Entrega actualizada exitosamente.');
            return redirect()->route('deliveries.index');
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
        return view('livewire.deliveries.delivery-edit');
    }
}