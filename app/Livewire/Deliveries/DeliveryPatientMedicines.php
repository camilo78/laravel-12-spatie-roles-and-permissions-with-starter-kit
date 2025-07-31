<?php

namespace App\Livewire\Deliveries;

use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use Livewire\Component;

class DeliveryPatientMedicines extends Component
{
    public DeliveryPatient $deliveryPatient;
    public $observations = [];
    public $isSubmitting = false;

    public function mount(DeliveryPatient $deliveryPatient)
    {
        $this->deliveryPatient = $deliveryPatient;
        $this->loadObservations();
    }

    public function loadObservations()
    {
        foreach ($this->deliveryPatient->deliveryMedicines as $medicine) {
            $this->observations[$medicine->id] = $medicine->observations;
        }
    }

    public function toggleMedicineInclusion($deliveryMedicineId)
    {
        if (!$this->deliveryPatient->medicineDelivery->isEditable()) return;
        
        $deliveryMedicine = DeliveryMedicine::find($deliveryMedicineId);
        $deliveryMedicine->update(['included' => !$deliveryMedicine->included]);
        
        $this->deliveryPatient->refresh();
        $this->dispatch('medicinesUpdated');
    }

    public function saveChanges()
    {
        if ($this->isSubmitting) return;
        if (!$this->deliveryPatient->medicineDelivery->isEditable()) return;
        
        $this->isSubmitting = true;
        
        try {
            $updated = 0;
            foreach ($this->deliveryPatient->deliveryMedicines as $deliveryMedicine) {
                if (!$deliveryMedicine->included && isset($this->observations[$deliveryMedicine->id])) {
                    $deliveryMedicine->update([
                        'observations' => $this->observations[$deliveryMedicine->id] ?? ''
                    ]);
                    $updated++;
                }
            }
            
            session()->flash('success', "Observaciones guardadas. {$updated} medicamentos actualizados.");
            $this->deliveryPatient->refresh();
            $this->loadObservations();
            $this->dispatch('medicinesUpdated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render()
    {
        return view('livewire.deliveries.delivery-patient-medicines');
    }
}