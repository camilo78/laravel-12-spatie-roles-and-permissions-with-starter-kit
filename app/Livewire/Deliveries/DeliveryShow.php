<?php

namespace App\Livewire\Deliveries;

use App\Traits\HasSearchableQueries;
use App\Models\MedicineDelivery;
use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

/**
 * Componente para mostrar detalles de una entrega con búsqueda de pacientes
 * Implementa el trait HasSearchableQueries para funcionalidad estandarizada
 */
class DeliveryShow extends Component
{
    use WithPagination, HasSearchableQueries;

    public MedicineDelivery $delivery;
    
    protected $listeners = ['medicinesUpdated' => '$refresh'];

    /**
     * Inicializa el componente con la entrega especificada
     * 
     * @param MedicineDelivery $delivery Entrega a mostrar
     */
    public function mount(MedicineDelivery $delivery)
    {
        $this->delivery = $delivery;
        // Configurar ordenamiento por defecto
        $this->sortField = 'user_id';
        $this->sortDirection = 'asc';
    }

    public function updatePatientState($deliveryPatientId, $newState)
    {
        if (!$this->delivery->isEditable()) {
            session()->flash('error', 'Esta entrega no es editable.');
            return;
        }
        
        $validStates = [DeliveryPatient::STATE_PROGRAMADA, DeliveryPatient::STATE_EN_PROCESO, DeliveryPatient::STATE_ENTREGADA, DeliveryPatient::STATE_NO_ENTREGADA];
        if (!in_array($newState, $validStates)) {
            session()->flash('error', 'Estado no válido.');
            return;
        }
        
        try {
            $deliveryPatient = DeliveryPatient::findOrFail($deliveryPatientId);
            $updateData = ['state' => $newState];
            
            // Limpiar notas si no es no_entregada
            if ($newState !== DeliveryPatient::STATE_NO_ENTREGADA) {
                $updateData['delivery_notes'] = null;
            }
            
            $deliveryPatient->update($updateData);
            
            $stateLabels = [
                'programada' => 'Programada',
                'en_proceso' => 'En Proceso',
                'entregada' => 'Entregada',
                'no_entregada' => 'No Entregada'
            ];
            
            session()->flash('success', "Estado actualizado a {$stateLabels[$newState]} exitosamente.");
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de paciente: ' . $e->getMessage(), [
                'delivery_patient_id' => $deliveryPatientId
            ]);
            session()->flash('error', 'Error al actualizar el estado del paciente.');
        }
    }
    
    public function updatePatientNotes($deliveryPatientId, $notes)
    {
        if (!$this->delivery->isEditable()) {
            session()->flash('error', 'Esta entrega no es editable.');
            return;
        }
        
        try {
            $deliveryPatient = DeliveryPatient::findOrFail($deliveryPatientId);
            
            if ($deliveryPatient->state !== DeliveryPatient::STATE_NO_ENTREGADA) {
                session()->flash('error', 'Solo se pueden agregar notas a pacientes no entregados.');
                return;
            }
            
            $deliveryPatient->update(['delivery_notes' => $notes]);
            session()->flash('success', 'Notas guardadas exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar notas de paciente: ' . $e->getMessage(), [
                'delivery_patient_id' => $deliveryPatientId
            ]);
            session()->flash('error', 'Error al guardar las notas.');
        }
    }

    /**
     * Cambia el estado de inclusión de un medicamento en la entrega
     * 
     * @param int $deliveryMedicineId ID del medicamento en la entrega
     */
    public function toggleMedicineInclusion($deliveryMedicineId)
    {
        if (!$this->delivery->isEditable()) {
            session()->flash('error', 'Esta entrega no es editable.');
            return;
        }
        
        try {
            $deliveryMedicine = DeliveryMedicine::findOrFail($deliveryMedicineId);
            $deliveryMedicine->update(['included' => !$deliveryMedicine->included]);
            
            $status = $deliveryMedicine->included ? 'incluido' : 'excluido';
            session()->flash('success', "Medicamento {$status} exitosamente.");
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar inclusión de medicamento: ' . $e->getMessage(), [
                'delivery_medicine_id' => $deliveryMedicineId
            ]);
            session()->flash('error', 'Error al actualizar el estado del medicamento.');
        }
    }

    /**
     * Actualiza las observaciones de un medicamento en la entrega
     * 
     * @param int $deliveryMedicineId ID del medicamento en la entrega
     * @param string $observations Nuevas observaciones
     */
    public function updateObservations($deliveryMedicineId, $observations)
    {
        if (!$this->delivery->isEditable()) {
            session()->flash('error', 'Esta entrega no es editable.');
            return;
        }
        
        try {
            $deliveryMedicine = DeliveryMedicine::findOrFail($deliveryMedicineId);
            $deliveryMedicine->update(['observations' => trim($observations)]);
            
            session()->flash('success', 'Observaciones actualizadas exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar observaciones: ' . $e->getMessage(), [
                'delivery_medicine_id' => $deliveryMedicineId
            ]);
            session()->flash('error', 'Error al actualizar las observaciones.');
        }
    }

    /**
     * Construye la consulta de pacientes de la entrega con filtros aplicados
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function buildDeliveryPatientsQuery()
    {
        try {
            // Consulta base con relaciones necesarias
            $query = $this->delivery->deliveryPatients()
                ->with(['user', 'deliveryMedicines.patientMedicine.medicine'])
                ->whereHas('deliveryMedicines')
                ->getQuery();

            // Aplicar búsqueda en usuarios relacionados
            $searchableFields = [
                'user.name' => 'relation',
                'user.dni' => 'relation',
                'user.phone' => 'relation'
            ];
            
            $query = $this->applySearch($query, $searchableFields);
            
            // Aplicar ordenamiento seguro
            $sortableFields = ['user_id', 'included', 'created_at'];
            $query = $this->applySorting($query, $sortableFields);
            
            return $this->executePaginatedQuery($query);
            
        } catch (\Exception $e) {
            Log::error('Error en consulta de pacientes de entrega: ' . $e->getMessage(), [
                'delivery_id' => $this->delivery->id,
                'search' => $this->search,
                'component' => get_class($this)
            ]);
            
            // Fallback: consulta simple sin filtros
            return $this->delivery->deliveryPatients()
                ->with(['user', 'deliveryMedicines'])
                ->paginate(10);
        }
    }

    /**
     * Renderiza el componente con la lista de pacientes filtrada
     */
    public function render()
    {
        $deliveryPatients = $this->buildDeliveryPatientsQuery();
        return view('livewire.deliveries.delivery-show', compact('deliveryPatients'));
    }
}