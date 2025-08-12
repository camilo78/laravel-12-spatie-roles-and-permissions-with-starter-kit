<?php

namespace App\Livewire\Medicines;

use App\Models\Medicine;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MedicineIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteMedicine(Medicine $medicine): void
    {
        $medicine->delete();
        session()->flash('success', 'Medicamento eliminado exitosamente.');
    }

    public function render(): View
    {
        $medicines = Medicine::when($this->search, function ($query) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('generic_name', 'like', "%{$this->search}%")
                  ->orWhere('presentation', 'like', "%{$this->search}%");
        })->paginate(10);

        return view('livewire.medicines.medicine-index', compact('medicines'));
    }
}