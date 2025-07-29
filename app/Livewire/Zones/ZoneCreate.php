<?php

namespace App\Livewire\Zones;

use App\Models\Municipality;
use App\Models\Zone;
use Livewire\Component;

class ZoneCreate extends Component
{
    public string $name = '';
    public string $description = '';
    public ?int $municipality_id = null;
    public $municipalities = [];

    public function mount()
    {
        $this->municipalities = Municipality::orderBy('name')->get();
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'municipality_id' => 'required|exists:municipalities,id',
        ]);

        Zone::create($validated);

        return to_route('zones.index')->with('success', 'Zona creada correctamente.');
    }

    public function render()
    {
        return view('livewire.zones.zone-create');
    }
}