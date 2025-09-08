<?php

namespace App\Livewire\Pathologies;

use App\Livewire\BaseIndexComponent;
use App\Models\Pathology;

/**
 * Componente para el índice de patologías
 * Extiende BaseIndexComponent para funcionalidad estandarizada
 */
class PathologyIndex extends BaseIndexComponent
{
    /**
     * Define los campos donde se puede buscar
     */
    protected function getSearchableFields(): array
    {
        return [
            'code' => 'like',
            'description' => 'like'
        ];
    }

    /**
     * Define los campos permitidos para ordenamiento
     */
    protected function getSortableFields(): array
    {
        return ['id', 'code', 'description', 'level', 'created_at'];
    }

    /**
     * Obtiene la clase del modelo Pathology
     */
    protected function getModelClass(): string
    {
        return Pathology::class;
    }

    /**
     * Renderiza el componente con la lista de patologías
     */
    public function render()
    {
        $pathologies = $this->buildQuery();
        return view('livewire.pathologies.pathology-index', compact('pathologies'));
    }

    /**
     * Elimina una patología
     */
    public function delete($id, $successMessage = 'Patología eliminada exitosamente.')
    {
        parent::delete($id, $successMessage);
    }
}