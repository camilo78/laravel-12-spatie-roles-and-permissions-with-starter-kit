<?php

namespace App\Livewire\Medicines;

use App\Models\Medicine;
use Livewire\Component;

class MedicineEdit extends Component
{
    public Medicine $medicine;
    public $name = '';
    public $generic_name = '';
    public $presentation = '';
    public $concentration = '';

    public function mount(Medicine $medicine)
    {
        $this->medicine = $medicine;
        $this->name = $medicine->name;
        $this->generic_name = $medicine->generic_name;
        $this->presentation = $medicine->presentation;
        $this->concentration = $medicine->concentration;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'generic_name' => 'required|string|max:255',
            'presentation' => 'required|string|max:255',
            'concentration' => 'required|string|max:255'
        ];
    }

    public function update()
    {
        $this->validate();

        $this->medicine->update([
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'presentation' => $this->presentation,
            'concentration' => $this->concentration
        ]);

        session()->flash('success', 'Medicamento actualizado exitosamente.');
        return redirect()->route('medicines.index');
    }

    public function render()
    {
        return view('livewire.medicines.medicine-edit');
    }
}