<?php

namespace App\Livewire\Localities;

use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Livewire\Component;

class LocalityEdit extends Component
{
    public Locality $locality;
    public $name = '';
    public $selectedDepartment = '';
    public $selectedMunicipality = '';
    public $isSubmitting = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'selectedMunicipality' => 'required|exists:municipalities,id',
    ];

    public function mount(Locality $locality)
    {
        $this->locality = $locality;
        $this->name = $locality->name;
        $this->selectedMunicipality = $locality->municipality_id;
        $this->selectedDepartment = $locality->municipality->department_id;
    }

    public function updatedSelectedDepartment()
    {
        if ($this->selectedDepartment != $this->locality->municipality->department_id) {
            $this->selectedMunicipality = '';
        }
    }

    public function update()
    {
        if ($this->isSubmitting) return;
        
        $this->isSubmitting = true;
        
        try {
            $this->validate();

            $this->locality->update([
                'name' => $this->name,
                'municipality_id' => $this->selectedMunicipality,
            ]);

            session()->flash('success', 'Localidad actualizada exitosamente.');
            return redirect()->route('localities.index', [
                'department_id' => $this->selectedDepartment,
                'municipality_id' => $this->selectedMunicipality
            ]);
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            throw $e;
        }
    }

    public function render()
    {
        $departments = Department::orderBy('name')->get();
        $municipalities = $this->selectedDepartment 
            ? Municipality::where('department_id', $this->selectedDepartment)->orderBy('name')->get()
            : collect();

        return view('livewire.localities.locality-edit', [
            'departments' => $departments,
            'municipalities' => $municipalities,
        ]);
    }
}