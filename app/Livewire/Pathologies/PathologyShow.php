<?php

namespace App\Livewire\Pathologies;

use App\Models\Pathology;
use Livewire\Component;

class PathologyShow extends Component
{
    public Pathology $pathology;

    public function mount(Pathology $pathology)
    {
        $this->pathology = $pathology;
    }

    public function render()
    {
        return view('livewire.pathologies.pathology-show');
    }
}