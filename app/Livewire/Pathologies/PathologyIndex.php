<?php

namespace App\Livewire\Pathologies;

use App\Models\Pathology;
use Livewire\Component;
use Livewire\WithPagination;

class PathologyIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Pathology::find($id)->delete();
        session()->flash('success', 'Patología eliminada exitosamente.');
    }

    public function render()
    {
        $pathologies = Pathology::when($this->search, function ($query) {
            $query->where('clave', 'like', '%' . $this->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $this->search . '%');
        })
        ->orderBy('clave')
        ->paginate(10);

        return view('livewire.pathologies.pathology-index', compact('pathologies'));
    }
}