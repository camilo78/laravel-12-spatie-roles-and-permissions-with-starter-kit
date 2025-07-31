<?php

namespace App\Livewire\Deliveries;

use App\Models\MedicineDelivery;
use Livewire\Component;

class DeliveryEdit extends Component
{
    public MedicineDelivery $delivery;
    public $name = '';
    public $start_date = '';
    public $end_date = '';
    public $isSubmitting = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
    ];

    public function mount(MedicineDelivery $delivery)
    {
        if (!$delivery->isEditable()) {
            abort(403, 'Esta entrega no puede ser editada.');
        }

        $this->delivery = $delivery;
        $this->name = $delivery->name;
        $this->start_date = $delivery->start_date->format('Y-m-d');
        $this->end_date = $delivery->end_date->format('Y-m-d');
    }

    public function save()
    {
        if ($this->isSubmitting || !$this->delivery->isEditable()) return;
        
        $this->isSubmitting = true;
        
        try {
            $this->validate();

            $this->delivery->update([
                'name' => $this->name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]);

            session()->flash('success', 'Entrega actualizada exitosamente.');
            return redirect()->route('deliveries.index');
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.deliveries.delivery-edit');
    }
}