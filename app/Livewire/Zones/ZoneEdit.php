<?php

namespace App\Livewire\Zones;

use App\Models\Municipality;
use App\Models\Zone;
use Livewire\Component;

class ZoneEdit extends Component
{
    public Zone $zone;
    public string $name = '';
    public string $description = '';
    public ?int $municipality_id = null;
    public $municipalities = [];

    public function mount(Zone $zone)
    {
        $this->zone = $zone;
        $this->name = $zone->name;
        $this->description = $zone->description;
        $this->municipality_id = $zone->municipality_id;
        $this->municipalities = Municipality::orderBy('name')->get();
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'municipality_id' => 'required|exists:municipalities,id',
        ]);

        $this->zone->update($validated);

        return to_route('zones.index')->with('success', 'Zona actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.zones.zone-edit');
    }
}