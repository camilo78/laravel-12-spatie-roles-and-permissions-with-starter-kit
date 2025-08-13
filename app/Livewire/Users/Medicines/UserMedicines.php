<?php

namespace App\Livewire\Users\Medicines;

use App\Models\User;
use App\Models\Medicine;
use App\Models\PatientMedicine;
use Livewire\Component;

class UserMedicines extends Component
{
    public User $user;
    public $medicine_id = '';
    public $dosage = '';
    public $quantity = '';
    public $start_date = '';
    public $end_date = '';
    public $status = '';

    protected $rules = [
        'medicine_id' => 'required|exists:medicines,id',
        'dosage' => 'required|string|max:255',
        'quantity' => 'required|integer|min:1',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'status' => 'required|in:active,suspended,completed'
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function assignMedicine()
    {
        $this->validate();

        $exists = PatientMedicine::where('user_id', $this->user->id)
            ->where('medicine_id', $this->medicine_id)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Este medicamento ya está asignado al usuario.');
            return;
        }

        PatientMedicine::create([
            'user_id' => $this->user->id,
            'medicine_id' => $this->medicine_id,
            'dosage' => $this->dosage,
            'quantity' => $this->quantity,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status
        ]);

        $this->reset(['medicine_id', 'dosage', 'quantity', 'start_date', 'end_date', 'status']);
        session()->flash('success', 'Medicamento asignado exitosamente.');
    }

    public function removeMedicine($id)
    {
        PatientMedicine::find($id)->delete();
        session()->flash('success', 'Medicamento removido exitosamente.');
    }

    public function render()
    {
        $userMedicines = PatientMedicine::where('user_id', $this->user->id)
            ->with('medicine')
            ->get();
        $medicines = Medicine::all();
        
        return view('livewire.users.medicines.user-medicines', compact('userMedicines', 'medicines'));
    }
}