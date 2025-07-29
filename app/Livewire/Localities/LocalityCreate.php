<?php

namespace App\Livewire\Localities;

use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Livewire\Component;

class LocalityCreate extends Component
{
    public $name = '';
    public $selectedDepartment = '';
    public $selectedMunicipality = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'selectedMunicipality' => 'required|exists:municipalities,id',
    ];

    public function mount()
    {
        $this->selectedDepartment = request('department_id', '');
        $this->selectedMunicipality = request('municipality_id', '');
    }

    public function updatedSelectedDepartment()
    {
        if ($this->selectedDepartment != request('department_id')) {
            $this->selectedMunicipality = '';
        }
    }

    public function save()
    {
        $this->validate();

        Locality::create([
            'name' => $this->name,
            'municipality_id' => $this->selectedMunicipality,
        ]);

        session()->flash('success', 'Localidad creada exitosamente.');
        return redirect()->route('localities.index', [
            'department_id' => $this->selectedDepartment,
            'municipality_id' => $this->selectedMunicipality
        ]);
    }

    public function render()
    {
        $departments = Department::orderBy('name')->get();
        $municipalities = $this->selectedDepartment 
            ? Municipality::where('department_id', $this->selectedDepartment)->orderBy('name')->get()
            : collect();

        return view('livewire.localities.locality-create', [
            'departments' => $departments,
            'municipalities' => $municipalities,
        ]);
    }
}