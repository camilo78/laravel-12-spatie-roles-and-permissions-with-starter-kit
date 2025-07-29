<?php

namespace App\Livewire\Localities;

use App\Models\Locality;
use Livewire\Component;

class LocalityShow extends Component
{
    public Locality $locality;

    public function mount(Locality $locality)
    {
        $this->locality = $locality->load('municipality.department');
    }

    public function render()
    {
        return view('livewire.localities.locality-show');
    }
}