<?php

namespace App\Livewire\Medicines;

use App\Models\Medicine;
use Livewire\Component;

class MedicineEdit extends Component
{
    public Medicine $medicine;
    public $generic_name = '';
    public $presentation = '';

    public function mount(Medicine $medicine)
    {
        $this->medicine = $medicine;
        $this->generic_name = $medicine->generic_name;
        $this->presentation = $medicine->presentation;
    }

    protected function rules()
    {
        return [
            'generic_name' => 'required|string|max:255',
            'presentation' => 'required|string|max:255',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->medicine->update([
            'generic_name' => $this->generic_name,
            'presentation' => $this->presentation,
        ]);

        session()->flash('success', 'Medicamento actualizado exitosamente.');
        return redirect()->route('medicines.index');
    }

    public function render()
    {
        return view('livewire.medicines.medicine-edit');
    }
}