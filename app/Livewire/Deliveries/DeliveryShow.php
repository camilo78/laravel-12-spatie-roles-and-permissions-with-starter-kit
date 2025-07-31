<?php

namespace App\Livewire\Deliveries;

use App\Models\MedicineDelivery;
use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryShow extends Component
{
    use WithPagination;

    public MedicineDelivery $delivery;
    public $search = '';

    public function mount(MedicineDelivery $delivery)
    {
        $this->delivery = $delivery;
    }

    public function togglePatientInclusion($deliveryPatientId)
    {
        if (!$this->delivery->isEditable()) return;
        
        $deliveryPatient = DeliveryPatient::find($deliveryPatientId);
        $deliveryPatient->update(['included' => !$deliveryPatient->included]);
    }

    public function render()
    {
        $deliveryPatients = $this->delivery->deliveryPatients()
            ->with(['user', 'deliveryMedicines.patientMedicine.medicine'])
            ->when($this->search, fn($query) => 
                $query->whereHas('user', fn($q) => 
                    $q->where('name', 'like', "%{$this->search}%")
                )
            )
            ->paginate(10);

        return view('livewire.deliveries.delivery-show', compact('deliveryPatients'));
    }
}