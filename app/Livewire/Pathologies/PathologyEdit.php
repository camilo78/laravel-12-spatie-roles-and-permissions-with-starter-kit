<?php

namespace App\Livewire\Pathologies;

use App\Models\Pathology;
use Livewire\Component;

class PathologyEdit extends Component
{
    public Pathology $pathology;
    public $clave = '';
    public $descripcion = '';

    public function mount(Pathology $pathology)
    {
        $this->pathology = $pathology;
        $this->clave = $pathology->clave;
        $this->descripcion = $pathology->descripcion;
    }

    protected function rules()
    {
        return [
            'clave' => 'required|string|max:50|unique:pathologies,clave,' . $this->pathology->id,
            'descripcion' => 'nullable|string'
        ];
    }

    public function update()
    {
        $this->validate();

        $this->pathology->update([
            'clave' => $this->clave,
            'descripcion' => $this->descripcion
        ]);

        session()->flash('success', 'Patología actualizada exitosamente.');
        return redirect()->route('pathologies.index');
    }



    public function render()
    {
        return view('livewire.pathologies.pathology-edit');
    }
}