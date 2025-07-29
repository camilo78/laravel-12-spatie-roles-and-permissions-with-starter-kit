<?php

namespace App\Livewire\Zones;

use App\Models\Municipality;
use App\Models\Zone;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Collection;

class ZoneIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $municipality_id = null;
    public Collection $municipalities;

    public function mount()
    {
        $this->municipalities = Municipality::orderBy('name')->get();
    }
    
    public function updatedMunicipalityId()
    {
        // Resetea la paginación cuando cambia el municipio
        $this->resetPage();
    }

    public function delete(Zone $zone)
    {
        $zone->delete();
        session()->flash('success', 'Zona eliminada correctamente.');
    }

    public function render()
    {
        $zones = collect(); // Por defecto, la colección de zonas está vacía

        // Solo busca y pagina las zonas si se ha seleccionado un municipio
        if ($this->municipality_id) {
            $zones = Zone::with('municipality')
                ->where('municipality_id', $this->municipality_id)
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->paginate(10);
        }

        return view('livewire.zones.zone-index', [
            'zones' => $zones,
        ]);
    }
}