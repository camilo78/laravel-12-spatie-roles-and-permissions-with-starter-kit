<?php

namespace App\Livewire\Pathologies;

use App\Models\Pathology;
use Livewire\Component;

class PathologyCreate extends Component
{
    public $clave = '';
    public $descripcion = '';

    protected $rules = [
        'clave' => 'required|string|max:50|unique:pathologies',
        'descripcion' => 'nullable|string'
    ];

    public function save()
    {
        $this->validate();

        Pathology::create([
            'clave' => $this->clave,
            'descripcion' => $this->descripcion
        ]);

        session()->flash('success', 'Patología creada exitosamente.');
        return redirect()->route('pathologies.index');
    }

    public function render()
    {
        return view('livewire.pathologies.pathology-create');
    }
}