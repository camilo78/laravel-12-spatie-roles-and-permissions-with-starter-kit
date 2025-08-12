<?php

namespace App\Livewire\Components;

use App\Models\Pathology;
use Livewire\Component;

class PathologySearch extends Component
{
    public $search = '';
    public $selectedPathologyId = '';
    public $pathologies = [];
    public $initialPathologyId = '';
    public $initialSearch = '';
    public $showDropdown = false;

    public function mount($initialPathologyId = '', $initialSearch = '')
    {
        $this->initialPathologyId = $initialPathologyId;
        $this->initialSearch = $initialSearch;
        $this->selectedPathologyId = $initialPathologyId;
        $this->search = $initialSearch;
    }

    public function updatedSearch()
    {
        $this->search = rtrim($this->search);
        
        if (strlen($this->search) < 2) {
            $this->pathologies = [];
            $this->showDropdown = false;
            return;
        }

        $term = strtoupper($this->search);
        
        $this->pathologies = Pathology::select('id', 'clave', 'descripcion')
            ->where('clave', 'like', "%{$term}%")
            ->orWhere('descripcion', 'like', "%{$term}%")
            ->orderBy('descripcion')
            ->limit(10)
            ->get();
            
        $this->showDropdown = true;
    }

    public function selectPathology($pathologyId)
    {
        $pathology = Pathology::find($pathologyId);
        if ($pathology) {
            $this->selectedPathologyId = $pathology->id;
            $this->search = $pathology->clave . ' - ' . $pathology->descripcion;
            $this->pathologies = [];
            $this->showDropdown = false;
        }
    }

    public function resetToInitial()
    {
        $this->search = $this->initialSearch;
        $this->selectedPathologyId = $this->initialPathologyId;
        $this->pathologies = [];
        $this->showDropdown = false;
    }

    public function hideDropdown()
    {
        $this->pathologies = [];
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.components.pathology-search');
    }
}