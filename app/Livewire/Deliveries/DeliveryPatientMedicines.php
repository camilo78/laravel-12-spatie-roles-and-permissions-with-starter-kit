<?php

namespace App\Livewire\Deliveries;

use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use Livewire\Component;

/**
 * Componente para gestionar los medicamentos de un paciente en una entrega específica.
 * Permite incluir/excluir medicamentos y agregar observaciones para exclusiones.
 */
class DeliveryPatientMedicines extends Component
{
    /** @var DeliveryPatient Paciente de la entrega actual */
    public DeliveryPatient $deliveryPatient;
    
    /** @var array Observaciones de medicamentos excluidos [id_medicamento => observacion] */
    public $observations = [];
    
    /** @var bool Previene múltiples envíos del formulario */
    public $isSubmitting = false;

    /**
     * Inicializa el componente con los datos del paciente.
     * Guarda la URL anterior para redirección posterior.
     */
    public function mount(DeliveryPatient $deliveryPatient)
    {
        $this->deliveryPatient = $deliveryPatient;
        $this->loadObservations();
        session(['previous_url' => url()->previous()]);
    }

    /**
     * Carga las observaciones existentes de medicamentos excluidos.
     */
    private function loadObservations()
    {
        foreach ($this->deliveryPatient->deliveryMedicines as $medicine) {
            $this->observations[$medicine->id] = $medicine->observations;
        }
    }

    /**
     * Alterna el estado de inclusión de un medicamento.
     * Si se incluye un medicamento, elimina automáticamente su observación.
     */
    public function toggleMedicineInclusion($deliveryMedicineId)
    {
        // Solo permitir edición si la entrega es editable
        if (!$this->deliveryPatient->medicineDelivery->isEditable()) return;
        
        $deliveryMedicine = DeliveryMedicine::find($deliveryMedicineId);
        $newIncludedState = !$deliveryMedicine->included;
        
        if ($newIncludedState) {
            // Medicamento incluido: eliminar observación
            $deliveryMedicine->update([
                'included' => true,
                'observations' => null
            ]);
            $this->observations[$deliveryMedicineId] = '';
        } else {
            // Medicamento excluido: mantener sin observación hasta que el usuario la escriba
            $deliveryMedicine->update(['included' => false]);
        }
        
        $this->deliveryPatient->refresh();
    }

    /**
     * Guarda los cambios realizados en las observaciones.
     * Valida que todos los medicamentos excluidos tengan observaciones.
     */
    public function saveChanges()
    {
        // Prevenir múltiples envíos y verificar permisos
        if ($this->isSubmitting || !$this->deliveryPatient->medicineDelivery->isEditable()) return;
        
        // Validar observaciones obligatorias para medicamentos excluidos
        foreach ($this->deliveryPatient->deliveryMedicines as $deliveryMedicine) {
            if (!$deliveryMedicine->included) {
                $observation = trim($this->observations[$deliveryMedicine->id] ?? '');
                if (empty($observation)) {
                    session()->flash('error', 'Debe especificar el motivo para todos los medicamentos no entregados.');
                    return;
                }
            }
        }
        
        $this->isSubmitting = true;
        
        try {
            $updated = 0;
            
            foreach ($this->deliveryPatient->deliveryMedicines as $deliveryMedicine) {
                if (!$deliveryMedicine->included) {
                    // Guardar observación para medicamentos excluidos
                    $deliveryMedicine->update([
                        'observations' => $this->observations[$deliveryMedicine->id]
                    ]);
                    $updated++;
                } elseif ($deliveryMedicine->observations) {
                    // Limpiar observaciones de medicamentos incluidos
                    $deliveryMedicine->update(['observations' => null]);
                    $updated++;
                }
            }
            
            session()->flash('success', "Observaciones guardadas. {$updated} medicamentos actualizados.");
            return redirect(session('previous_url', route('deliveries.weekly-schedule')));
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('livewire.deliveries.delivery-patient-medicines');
    }
}