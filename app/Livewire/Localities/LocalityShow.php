<?php

namespace App\Livewire\Localities;

use App\Models\Locality;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Componente Livewire para mostrar los detalles de una localidad
 * 
 * Este componente maneja la visualización detallada de una localidad
 * incluyendo su municipio y departamento asociados
 * 
 * @package App\Livewire\Localities
 */
class LocalityShow extends Component
{
    /**
     * Instancia de la localidad a mostrar
     * 
     * @var Locality
     */
    public Locality $locality;

    /**
     * Inicializa el componente con la localidad y sus relaciones
     * 
     * @param Locality $locality Localidad a mostrar
     * @return void
     */
    public function mount(Locality $locality): void
    {
        // Cargar la localidad con sus relaciones (municipio y departamento)
        $this->locality = $locality->load('municipality.department');
    }

    /**
     * Renderiza la vista del componente
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.localities.locality-show');
    }
}