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
        $medicines = Medicine::when(trim($this->search), function ($query) {
            $searchTerm = trim($this->search);
            $query->where('generic_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('presentation', 'like', '%' . $searchTerm . '%');
        })
        ->orderBy('generic_name')
        ->paginate(10);

        return view('livewire.medicines.medicine-index', compact('medicines'));
    }
}