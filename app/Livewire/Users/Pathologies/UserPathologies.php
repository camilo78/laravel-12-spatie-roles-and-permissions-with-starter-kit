<?php

namespace App\Livewire\Users\Pathologies;

use App\Models\User;
use App\Models\Pathology;
use App\Models\PatientPathology;
use Livewire\Component;

class UserPathologies extends Component
{
    public User $user;
    public $pathology_id = '';
    public $diagnosed_at = '';
    public $status = '';

    protected $rules = [
        'pathology_id' => 'required|exists:pathologies,id',
        'diagnosed_at' => 'required|date',
        'status' => 'required|in:active,inactive,controlled'
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function assignPathology()
    {
        $this->validate();

        PatientPathology::create([
            'user_id' => $this->user->id,
            'pathology_id' => $this->pathology_id,
            'diagnosed_at' => $this->diagnosed_at,
            'status' => $this->status
        ]);

        $this->reset(['pathology_id', 'diagnosed_at', 'status']);
        session()->flash('success', 'Patología asignada correctamente');
    }

    public function removePathology($id)
    {
        PatientPathology::find($id)->delete();
        session()->flash('success', 'Patología eliminada correctamente');
    }

    public function render()
    {
        $patientPathologies = $this->user->patientPathologies()->with('pathology')->get();
        $pathologies = Pathology::orderBy('clave')->get();
        
        return view('livewire.users.pathologies.user-pathologies', compact('patientPathologies', 'pathologies'));
    }
}