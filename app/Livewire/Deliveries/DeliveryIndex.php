<?php

namespace App\Livewire\Deliveries;

use App\Models\MedicineDelivery;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $deliveries = MedicineDelivery::query()
            ->when($this->search, fn($query) => 
                $query->where('name', 'like', "%{$this->search}%")
            )
            ->latest()
            ->paginate(10);

        return view('livewire.deliveries.delivery-index', compact('deliveries'));
    }
}