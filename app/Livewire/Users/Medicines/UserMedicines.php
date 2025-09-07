<?php

namespace App\Livewire\Users\Medicines;

use App\Models\User;
use App\Models\Medicine;
use App\Models\PatientMedicine;
use App\Models\MedicineDelivery;
use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use Livewire\Component;

class UserMedicines extends Component
{
    // Usuario al que se asignarán medicamentos
    public User $user;

    // Campos del formulario
    public $medicine_id;
    public string $medicine_search = '';
    public $dosage;
    public $quantity;
    public $start_date;
    public $end_date;
    public $status;
    
    // Para búsqueda de medicamentos
    public $filtered_medicines = [];

    // ID del medicamento en edición (null = modo asignar)
    public $editingId = null;

    // Reglas de validación
    protected $rules = [
        'medicine_id' => 'required|exists:medicines,id',
        'dosage'      => 'required|string|max:255',
        'quantity'    => 'required|integer|min:1',
        'start_date'  => 'required|date',
        'end_date'    => 'nullable|date|after:start_date',
        'status'      => 'required|in:active,suspended,completed'
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    /**
     * Se ejecuta cuando cambia el texto de búsqueda de medicamento
     */
    public function updatedMedicineSearch($value)
    {
        if (strlen($value) < 2) {
            $this->filtered_medicines = [];
            return;
        }

        $this->filtered_medicines = Medicine::where('generic_name', 'like', '%' . $value . '%')
            ->orWhere('presentation', 'like', '%' . $value . '%')
            ->limit(10)
            ->get();
    }

    /**
     * Selecciona un medicamento de la lista filtrada
     */
    public function selectMedicine(int $id, string $name): void
    {
        $this->medicine_id = $id;
        $this->medicine_search = $name;
        $this->filtered_medicines = [];
    }

    /**
     * Método principal que decide si asigna o edita según el modo
     */
    public function saveMedicine()
    {
        if ($this->editingId) {
            $this->editMedicine();
        } else {
            $this->assignMedicine();
        }
    }

    /**
     * Asignar nuevo medicamento
     */
    public function assignMedicine()
    {
        $this->validate();

        // Solo validar duplicados al asignar nuevo medicamento
        if ($this->medicineExists($this->medicine_id)) {
            session()->flash('error', 'Este medicamento ya está asignado al usuario.');
            return;
        }

        $patientMedicine = PatientMedicine::create($this->getMedicineData());
        
        // Solo agregar a entregas editables si el estado es activo
        $addedCount = 0;
        if ($this->status === 'active') {
            $addedCount = $this->addToEditableDeliveries($patientMedicine);
        }

        $this->resetForm();
        $message = 'Medicamento asignado exitosamente.';
        if ($addedCount > 0) {
            $message .= " Agregado a {$addedCount} entrega(s) editable(s).";
        }
        session()->flash('success', $message);
    }

    /**
     * Cargar datos de un medicamento para edición
     */
    public function loadMedicine($id)
    {
        $medicine = PatientMedicine::find($id);

        if (!$medicine) {
            session()->flash('error', 'El medicamento no existe.');
            return;
        }

        $this->editingId   = $medicine->id;
        $this->medicine_id = $medicine->medicine_id;
        $this->medicine_search = $medicine->medicine->generic_name . ' - ' . $medicine->medicine->presentation;
        $this->dosage      = $medicine->dosage;
        $this->quantity    = $medicine->quantity;
        $this->start_date  = $medicine->start_date ? $medicine->start_date->format('Y-m-d') : null;
        $this->end_date    = $medicine->end_date ? $medicine->end_date->format('Y-m-d') : null;
        $this->status      = $medicine->status;
    }

    /**
     * Editar medicamento existente
     */
    public function editMedicine()
    {
        $this->validate();

        $medicine = PatientMedicine::find($this->editingId);

        if (!$medicine) {
            session()->flash('error', 'El registro de medicamento no existe.');
            return;
        }

        $oldStatus = $medicine->status;
        $newStatus = $this->status;
        
        // Actualizar medicamento
        $medicine->update($this->getMedicineData());
        
        // Manejar cambios de estado en entregas
        $this->handleStatusChange($medicine, $oldStatus, $newStatus);

        $this->resetForm();
        $this->editingId = null;
        session()->flash('success', 'Medicamento actualizado exitosamente.');
    }

    /**
     * Eliminar medicamento asignado
     */
    public function removeMedicine($id)
    {
        $medicine = PatientMedicine::find($id);

        if (!$medicine) {
            session()->flash('error', 'El registro de medicamento no existe.');
            return;
        }

        // Eliminar de entregas editables antes de eliminar el medicamento
        $this->removeFromEditableDeliveries($medicine);
        
        $medicine->delete();
        session()->flash('success', 'Medicamento removido exitosamente y eliminado de entregas editables.');
    }

    /**
     * Verificar si ya existe un medicamento asignado
     * Solo se usa al crear, no al editar
     */
    private function medicineExists($medicineId)
    {
        return PatientMedicine::where('user_id', $this->user->id)
            ->where('medicine_id', $medicineId)
            ->exists();
    }

    /**
     * Obtener los datos del formulario listos para guardar
     */
    private function getMedicineData()
    {
        return [
            'user_id'     => $this->user->id,
            'medicine_id' => $this->medicine_id,
            'dosage'      => $this->dosage,
            'quantity'    => $this->quantity,
            'start_date'  => $this->start_date,
            'end_date'    => $this->end_date,
            'status'      => $this->status
        ];
    }

    /**
     * Agregar medicamento a entregas editables
     */
    private function addToEditableDeliveries(PatientMedicine $patientMedicine)
    {
        // Obtener entregas editables (fecha de inicio posterior a hoy)
        $editableDeliveries = MedicineDelivery::where('start_date', '>', now()->toDateString())->get();
        $addedCount = 0;
        
        foreach ($editableDeliveries as $delivery) {
            // Verificar si el usuario ya está en esta entrega
            $deliveryPatient = DeliveryPatient::where('medicine_delivery_id', $delivery->id)
                ->where('user_id', $this->user->id)
                ->first();
            
            // Si el usuario está en la entrega, agregar el medicamento
            if ($deliveryPatient) {
                // Verificar que el medicamento no esté ya agregado
                $existingMedicine = DeliveryMedicine::where('delivery_patient_id', $deliveryPatient->id)
                    ->where('patient_medicine_id', $patientMedicine->id)
                    ->exists();
                
                if (!$existingMedicine) {
                    DeliveryMedicine::create([
                        'delivery_patient_id' => $deliveryPatient->id,
                        'patient_medicine_id' => $patientMedicine->id,
                        'included' => true
                    ]);
                    $addedCount++;
                }
            }
        }
        
        return $addedCount;
    }

    /**
     * Manejar cambios de estado del medicamento
     */
    private function handleStatusChange(PatientMedicine $patientMedicine, $oldStatus, $newStatus)
    {
        // Si cambia de no activo a activo, agregar a entregas
        if ($oldStatus !== 'active' && $newStatus === 'active') {
            $this->addToEditableDeliveries($patientMedicine);
        }
        // Si cambia de activo a no activo, eliminar de entregas
        elseif ($oldStatus === 'active' && $newStatus !== 'active') {
            $this->removeFromEditableDeliveries($patientMedicine);
        }
    }

    /**
     * Eliminar medicamento de entregas editables
     */
    private function removeFromEditableDeliveries(PatientMedicine $patientMedicine)
    {
        // Obtener entregas editables
        $editableDeliveries = MedicineDelivery::where('start_date', '>', now()->toDateString())->get();
        $removedCount = 0;
        
        foreach ($editableDeliveries as $delivery) {
            // Verificar si el usuario está en esta entrega
            $deliveryPatient = DeliveryPatient::where('medicine_delivery_id', $delivery->id)
                ->where('user_id', $this->user->id)
                ->first();
            
            if ($deliveryPatient) {
                // Eliminar el medicamento de la entrega
                $deleted = DeliveryMedicine::where('delivery_patient_id', $deliveryPatient->id)
                    ->where('patient_medicine_id', $patientMedicine->id)
                    ->delete();
                
                if ($deleted) {
                    $removedCount++;
                }
            }
        }
        
        return $removedCount;
    }

    /**
     * Limpiar campos del formulario
     */
    private function resetForm()
    {
        $this->reset(['medicine_id', 'medicine_search', 'dosage', 'quantity', 'start_date', 'end_date', 'status']);
        $this->filtered_medicines = [];
    }

    public function render()
    {
        return view('livewire.users.medicines.user-medicines', [
            'userMedicines' => PatientMedicine::where('user_id', $this->user->id)
                ->with('medicine')
                ->get()
        ]);
    }
}
