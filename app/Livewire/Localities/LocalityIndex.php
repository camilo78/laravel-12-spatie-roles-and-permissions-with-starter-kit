<?php

namespace App\Livewire\Localities;

use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Livewire\Component;
use Livewire\WithPagination;

class LocalityIndex extends Component
{
    use WithPagination;

    public $selectedDepartment = '';
    public $selectedMunicipality = '';
    public $search = '';

    public function mount()
    {
        $this->selectedDepartment = request('department_id', '');
        $this->selectedMunicipality = request('municipality_id', '');
    }

    public function updatedSelectedDepartment()
    {
        $this->selectedMunicipality = '';
        $this->resetPage();
    }

    public function updatedSelectedMunicipality()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function deleteLocality($localityId)
    {
        $locality = Locality::find($localityId);
        if ($locality) {
            $locality->delete();
            session()->flash('success', 'Localidad eliminada exitosamente.');
        }
    }

    public function render()
    {
        $departments = Department::get();
        $municipalities = $this->selectedDepartment 
            ? Municipality::where('department_id', $this->selectedDepartment)->get()
            : collect();

        $localities = collect();
        if ($this->selectedMunicipality) {
            $localities = Locality::with('municipality')
                ->where('municipality_id', $this->selectedMunicipality)
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->paginate(10);
        }

        return view('livewire.localities.locality-index', [
            'departments' => $departments,
            'municipalities' => $municipalities,
            'localities' => $localities,
        ]);
    }
}