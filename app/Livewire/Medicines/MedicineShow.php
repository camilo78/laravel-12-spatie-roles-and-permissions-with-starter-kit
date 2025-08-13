<?php

namespace App\Livewire\Medicines;

use App\Models\Medicine;
use Livewire\Component;

class MedicineShow extends Component
{
    public Medicine $medicine;

    public function mount(Medicine $medicine)
    {
        $this->medicine = $medicine;
    }

    public function render()
    {
        return view('livewire.medicines.medicine-show');
    }
}