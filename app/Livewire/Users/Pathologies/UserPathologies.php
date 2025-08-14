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
    public $editingId = null;

    protected $rules = [
        'pathology_id' => 'required|exists:pathologies,id',
        'diagnosed_at' => 'required|date',
        'status' => 'required|in:active,inactive,controlled'
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function savePathology()
    {
        if ($this->editingId) {
            $this->updatePathology();
        } else {
            $this->assignPathology();
        }
    }

    public function assignPathology()
    {
        $this->validate();

        if ($this->pathologyExists($this->pathology_id)) {
            session()->flash('error', 'Esta patología ya está asignada al usuario.');
            return;
        }

        PatientPathology::create([
            'user_id' => $this->user->id,
            'pathology_id' => $this->pathology_id,
            'diagnosed_at' => $this->diagnosed_at,
            'status' => $this->status
        ]);

        $this->resetForm();
        session()->flash('success', 'Patología asignada correctamente');
    }

    public function loadPathology($id)
    {
        $pathology = PatientPathology::find($id);

        if (!$pathology) {
            session()->flash('error', 'La patología no existe.');
            return;
        }

        $this->editingId = $pathology->id;
        $this->pathology_id = $pathology->pathology_id;
        $this->diagnosed_at = $pathology->diagnosed_at->format('Y-m-d');
        $this->status = $pathology->status;
    }

    public function updatePathology()
    {
        $this->validate();

        $pathology = PatientPathology::find($this->editingId);

        if (!$pathology) {
            session()->flash('error', 'El registro de patología no existe.');
            return;
        }

        $pathology->update([
            'pathology_id' => $this->pathology_id,
            'diagnosed_at' => $this->diagnosed_at,
            'status' => $this->status
        ]);

        $this->resetForm();
        $this->editingId = null;
        session()->flash('success', 'Patología actualizada correctamente');
    }

    public function removePathology($id)
    {
        $pathology = PatientPathology::find($id);

        if (!$pathology) {
            session()->flash('error', 'El registro de patología no existe.');
            return;
        }

        $pathology->delete();
        session()->flash('success', 'Patología eliminada correctamente');
    }

    private function pathologyExists($pathologyId)
    {
        return PatientPathology::where('user_id', $this->user->id)
            ->where('pathology_id', $pathologyId)
            ->exists();
    }

    private function resetForm()
    {
        $this->reset(['pathology_id', 'diagnosed_at', 'status']);
    }

    public function render()
    {
        $patientPathologies = $this->user->patientPathologies()->with('pathology')->get();
        $pathologies = Pathology::orderBy('clave')->get();
        
        return view('livewire.users.pathologies.user-pathologies', [
            'patientPathologies' => $patientPathologies,
            'pathologies' => $pathologies
        ]);
    }
}