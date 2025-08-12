<?php

namespace App\Livewire\Deliveries;

use App\Models\MedicineDelivery;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Componente Livewire para mostrar el listado de entregas de medicamentos
 * 
 * Este componente maneja la visualización y filtrado de entregas
 * de medicamentos con paginación
 * 
 * @package App\Livewire\Deliveries
 */
class DeliveryIndex extends Component
{
    use WithPagination;

    /**
     * Término de búsqueda para filtrar entregas
     * 
     * @var string
     */
    public $search = '';

    /**
     * Se ejecuta cuando cambia el término de búsqueda
     * Resetea la paginación
     * 
     * @return void
     */
    public function updatedSearch(): void
    {
        // Resetear paginación al cambiar búsqueda
        $this->resetPage();
    }

    /**
     * Renderiza la vista del componente con entregas filtradas
     * 
     * @return View
     */
    public function render(): View
    {
        // Obtener entregas con filtro de búsqueda y paginación
        $deliveries = MedicineDelivery::query()
            ->when($this->search, fn($query) => 
                // Filtrar por nombre si hay término de búsqueda
                $query->where('name', 'like', "%{$this->search}%")
            )
            ->latest() // Ordenar por fecha de creación descendente
            ->paginate(10);

        return view('livewire.deliveries.delivery-index', compact('deliveries'));
    }
}