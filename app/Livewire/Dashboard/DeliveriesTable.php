<?php

namespace App\Livewire\Dashboard;

use App\Models\MedicineDelivery;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveriesTable extends Component
{
    use WithPagination;

    public function render()
    {
        $deliveries = MedicineDelivery::with([
            'deliveryPatients.deliveryMedicines',
            'deliveryPatients.user'
        ])->orderBy('start_date', 'desc')->paginate(6);

        return view('livewire.dashboard.deliveries-table', compact('deliveries'));
    }
}