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
    public string $pathology_search = '';
    public $diagnosed_at = '';
    public $status = '';
    public $editingId = null;
    
    // Para búsqueda de patologías
    public $filtered_pathologies = [];

    protected $rules = [
        'pathology_id' => 'required|exists:pathologies,id',
        'diagnosed_at' => 'required|date',
        'status' => 'required|in:active,inactive,controlled'
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    /**
     * Se ejecuta cuando cambia el texto de búsqueda de patología
     */
    public function updatedPathologySearch($value)
    {
        if (strlen($value) < 2) {
            $this->filtered_pathologies = [];
            return;
        }

        $this->filtered_pathologies = Pathology::where('clave', 'like', '%' . $value . '%')
            ->orWhere('descripcion', 'like', '%' . $value . '%')
            ->orderBy('clave')
            ->limit(10)
            ->get();
    }

    /**
     * Selecciona una patología de la lista filtrada
     */
    public function selectPathology(int $id, string $name): void
    {
        $this->pathology_id = $id;
        $this->pathology_search = $name;
        $this->filtered_pathologies = [];
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
        $this->pathology_search = $pathology->pathology->clave . ' - ' . $pathology->pathology->descripcion;
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
        $this->reset(['pathology_id', 'pathology_search', 'diagnosed_at', 'status']);
        $this->filtered_pathologies = [];
    }

    public function render()
    {
        return view('livewire.users.pathologies.user-pathologies', [
            'patientPathologies' => $this->user->patientPathologies()->with('pathology')->get()
        ]);
    }
}