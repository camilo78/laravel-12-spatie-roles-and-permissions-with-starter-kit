<?php

namespace App\Livewire\Medicines;

use App\Livewire\BaseIndexComponent;
use App\Models\Medicine;

/**
 * Componente para el índice de medicamentos
 * Extiende BaseIndexComponent para funcionalidad estandarizada
 */
class MedicineIndex extends BaseIndexComponent
{
    /**
     * Define los campos donde se puede buscar
     */
    protected function getSearchableFields(): array
    {
        return [
            'generic_name' => 'like',
            'presentation' => 'like'
        ];
    }

    /**
     * Define los campos permitidos para ordenamiento
     */
    protected function getSortableFields(): array
    {
        return ['id', 'generic_name', 'presentation', 'created_at'];
    }

    /**
     * Obtiene la clase del modelo Medicine
     */
    protected function getModelClass(): string
    {
        return Medicine::class;
    }

    /**
     * Renderiza el componente con la lista de medicamentos
     */
    public function render()
    {
        $medicines = $this->buildQuery();
        return view('livewire.medicines.medicine-index', compact('medicines'));
    }

    /**
     * Elimina un medicamento
     */
    public function delete($id, $successMessage = 'Medicamento eliminado exitosamente.')
    {
        parent::delete($id, $successMessage);
    }
}