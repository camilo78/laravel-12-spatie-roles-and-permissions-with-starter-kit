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
    
    protected $listeners = ['medicinesUpdated' => '$refresh'];

    public function mount(MedicineDelivery $delivery)
    {
        $this->delivery = $delivery;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function togglePatientInclusion($deliveryPatientId)
    {
        if (!$this->delivery->isEditable()) return;
        
        $deliveryPatient = DeliveryPatient::find($deliveryPatientId);
        $deliveryPatient->update(['included' => !$deliveryPatient->included]);
    }

    public function toggleMedicineInclusion($deliveryMedicineId)
    {
        if (!$this->delivery->isEditable()) return;
        
        $deliveryMedicine = DeliveryMedicine::find($deliveryMedicineId);
        $deliveryMedicine->update(['included' => !$deliveryMedicine->included]);
    }

    public function updateObservations($deliveryMedicineId, $observations)
    {
        if (!$this->delivery->isEditable()) return;
        
        DeliveryMedicine::find($deliveryMedicineId)->update(['observations' => $observations]);
        session()->flash('success', 'Observaciones actualizadas.');
    }

    public function render()
    {
        $deliveryPatients = $this->delivery->deliveryPatients()
            ->with(['user', 'deliveryMedicines.patientMedicine.medicine'])
            ->when($this->search, fn($query) => 
                $query->whereHas('user', fn($q) => 
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('dui', 'like', "%{$this->search}%")
                )
            )
            ->paginate(10);

        return view('livewire.deliveries.delivery-show', compact('deliveryPatients'));
    }
}