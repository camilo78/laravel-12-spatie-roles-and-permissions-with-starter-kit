<?php

namespace App\Livewire\Users\Medicines;

use App\Models\User;
use App\Models\Medicine;
use App\Models\PatientMedicine;
use Livewire\Component;

class UserMedicines extends Component
{
    // Usuario al que se asignarán medicamentos
    public User $user;

    // Campos del formulario
    public $medicine_id;
    public $dosage;
    public $quantity;
    public $start_date;
    public $end_date;
    public $status;

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

        PatientMedicine::create($this->getMedicineData());

        $this->resetForm();
        session()->flash('success', 'Medicamento asignado exitosamente.');
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

        // No validamos duplicados al editar
        $medicine->update($this->getMedicineData());

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

        $medicine->delete();
        session()->flash('success', 'Medicamento removido exitosamente.');
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
     * Limpiar campos del formulario
     */
    private function resetForm()
    {
        $this->reset(['medicine_id', 'dosage', 'quantity', 'start_date', 'end_date', 'status']);
    }

    public function render()
    {
        return view('livewire.users.medicines.user-medicines', [
            'userMedicines' => PatientMedicine::where('user_id', $this->user->id)
                ->with('medicine')
                ->get(),
            'medicines' => Medicine::all()
        ]);
    }
}
