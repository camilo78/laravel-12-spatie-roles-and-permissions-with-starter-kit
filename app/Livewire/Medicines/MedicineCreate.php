<?php

namespace App\Livewire\Medicines;

use App\Models\Medicine;
use Livewire\Component;

class MedicineCreate extends Component
{
    public $name = '';
    public $generic_name = '';
    public $presentation = '';

    protected $rules = [
        'generic_name' => 'required|string|max:255',
        'presentation' => 'required|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        Medicine::create([
            'generic_name' => $this->generic_name,
            'presentation' => $this->presentation,
        ]);

        session()->flash('success', 'Medicamento creado exitosamente.');
        return redirect()->route('medicines.index');
    }

    public function render()
    {
        return view('livewire.medicines.medicine-create');
    }
}