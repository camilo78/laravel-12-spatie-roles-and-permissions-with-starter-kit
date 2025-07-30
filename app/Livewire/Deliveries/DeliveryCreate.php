<?php

namespace App\Livewire\Deliveries;

use App\Models\MedicineDelivery;
use App\Models\User;
use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DeliveryCreate extends Component
{
    public $name = '';
    public $start_date = '';
    public $end_date = '';
    public $isSubmitting = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'start_date' => 'required|date|after:today',
        'end_date' => 'required|date|after:start_date',
    ];
    
    public function save()
    {
        if ($this->isSubmitting) return;
        
        $this->isSubmitting = true;
        
        try {
            $this->validate();

            DB::transaction(function () {
                $delivery = MedicineDelivery::create([
                    'name' => $this->name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                ]);

                $activeUsers = User::where('status', true)->get();
                
                foreach ($activeUsers as $user) {
                    $deliveryPatient = DeliveryPatient::create([
                        'medicine_delivery_id' => $delivery->id,
                        'user_id' => $user->id,
                    ]);

                    $activeMedicines = $user->patientPathologies()
                        ->where('status', 'active')
                        ->with(['patientMedicines' => fn($q) => $q->where('status', 'active')])
                        ->get()
                        ->flatMap->patientMedicines;

                    foreach ($activeMedicines as $medicine) {
                        DeliveryMedicine::create([
                            'delivery_patient_id' => $deliveryPatient->id,
                            'patient_medicine_id' => $medicine->id,
                        ]);
                    }
                }
            });

            session()->flash('success', 'Entrega creada exitosamente.');
            return redirect()->route('deliveries.index');
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.deliveries.delivery-create');
    }
}