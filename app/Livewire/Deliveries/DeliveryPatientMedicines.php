<?php

namespace App\Livewire\Deliveries;

use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use Livewire\Component;

class DeliveryPatientMedicines extends Component
{
    public DeliveryPatient $deliveryPatient;

    public function mount(DeliveryPatient $deliveryPatient)
    {
        $this->deliveryPatient = $deliveryPatient;
    }

    public function toggleMedicineInclusion($deliveryMedicineId)
    {
        if (!$this->deliveryPatient->medicineDelivery->isEditable()) return;
        
        $deliveryMedicine = DeliveryMedicine::find($deliveryMedicineId);
        $deliveryMedicine->update(['included' => !$deliveryMedicine->included]);
    }

    public function render()
    {
        return view('livewire.deliveries.delivery-patient-medicines');
    }
}