<?php

namespace App\Livewire\Medicines;

use App\Models\Medicine;
use Livewire\Component;
use Livewire\WithPagination;

class MedicineIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Medicine::find($id)->delete();
        session()->flash('success', 'Medicamento eliminado exitosamente.');
    }

    public function render()
    {
        $medicines = Medicine::when($this->search, function ($query) {
            $query->Where('generic_name', 'like', '%' . $this->search . '%')
                  ->orWhere('presentation', 'like', '%' . $this->search . '%');
        })
        ->orderBy('generic_name')
        ->paginate(10);

        return view('livewire.medicines.medicine-index', compact('medicines'));
    }
}