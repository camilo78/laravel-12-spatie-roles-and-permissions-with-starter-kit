<?php

namespace App\Livewire\Deliveries;

use App\Livewire\BaseIndexComponent;
use App\Models\MedicineDelivery;
use Illuminate\View\View;

/**
 * Componente para el índice de entregas de medicamentos
 * Extiende BaseIndexComponent para funcionalidad estandarizada
 */
class DeliveryIndex extends BaseIndexComponent
{
    /**
     * Define los campos donde se puede buscar
     */
    protected function getSearchableFields(): array
    {
        return [
            'name' => 'like'
        ];
    }

    /**
     * Define los campos permitidos para ordenamiento
     */
    protected function getSortableFields(): array
    {
        return ['id', 'name', 'start_date', 'end_date', 'created_at'];
    }

    /**
     * Obtiene la clase del modelo MedicineDelivery
     */
    protected function getModelClass(): string
    {
        return MedicineDelivery::class;
    }

    /**
     * Define las relaciones a cargar con eager loading
     */
    protected function getEagerLoadRelations(): array
    {
        return ['deliveryPatients'];
    }

    /**
     * Elimina una entrega y todas sus relaciones
     */
    public function deleteDelivery($deliveryId)
    {
        $delivery = MedicineDelivery::findOrFail($deliveryId);
        
        if (!$delivery->isDeletable()) {
            session()->flash('error', 'Solo se pueden eliminar entregas editables.');
            return;
        }
        
        $delivery->delete();
        session()->flash('success', 'Entrega eliminada exitosamente.');
    }

    /**
     * Renderiza el componente con la lista de entregas
     */
    public function render(): View
    {
        $deliveries = $this->buildQuery();
        return view('livewire.deliveries.delivery-index', compact('deliveries'));
    }
}