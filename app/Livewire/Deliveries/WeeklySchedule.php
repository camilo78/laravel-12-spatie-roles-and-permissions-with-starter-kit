<?php

namespace App\Livewire\Deliveries;

use App\Models\User;
use App\Models\DeliveryPatient;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WeeklySchedule extends Component
{
    use WithPagination;
    
    public $search = '';
    public $startDate = '';
    public $endDate = '';
    public $weekStart = '';
    public $weekEnd = '';

    public function updatedSearch($value)
    {
        $this->search = trim($value);
        $this->resetPage();
    }

    public function updatePatientState($deliveryPatientId, $state)
    {
        $deliveryPatient = DeliveryPatient::findOrFail($deliveryPatientId);
        $deliveryPatient->update(['state' => $state]);
    }

    public function updatePatientNotes($deliveryPatientId, $notes)
    {
        $deliveryPatient = DeliveryPatient::findOrFail($deliveryPatientId);
        $deliveryPatient->update(['delivery_notes' => $notes]);
    }

    public function exportExcel()
    {
        $startOfWeek = Carbon::parse($this->startDate);
        $endOfWeek = Carbon::parse($this->endDate);
        
        $patients = User::query()
            ->whereNotNull('admission_date')
            ->where('status', true)
            ->with(['municipality', 'locality'])
            ->get()
            ->filter(function($patient) use ($startOfWeek, $endOfWeek) {
                $patient->next_delivery_date = $patient->getNextDeliveryDate();
                return $patient->next_delivery_date && 
                       $patient->next_delivery_date->between($startOfWeek, $endOfWeek);
            });

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DeliveryPatientsExport($patients), 
            'entregas_' . $this->startDate . '_' . $this->endDate . '.xlsx'
        );
    }

    public function render()
    {
        $startOfWeek = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfMonth();
        $endOfWeek = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now()->endOfMonth();
        
        $this->weekStart = $startOfWeek->format('d/m/Y');
        $this->weekEnd = $endOfWeek->format('d/m/Y');

        $query = User::query()
            ->whereNotNull('admission_date')
            ->where('status', true)
            ->with(['department', 'municipality', 'locality', 'deliveryPatients']);

        if ($this->search) {
            $searchTerm = trim($this->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('dni', 'like', '%' . $searchTerm . '%')
                  ->orWhere('phone', 'like', '%' . $searchTerm . '%');
            });
        }

        $allPatients = $query->get();
        
        // Calcular next_delivery_date usando la función del modelo
        $allPatients->each(function($patient) {
            $patient->next_delivery_date = $patient->getNextDeliveryDate();
        });
        
        // Filtrar por fechas del mes en curso
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        
        // Limitar las fechas de filtro al mes actual
        if ($this->startDate) {
            $startOfWeek = max($startOfWeek, $currentMonthStart);
        }
        if ($this->endDate) {
            $endOfWeek = min($endOfWeek, $currentMonthEnd);
        }
        
        // Filtrar pacientes por fechas del mes en curso
        $allPatients = $allPatients->filter(function($patient) use ($startOfWeek, $endOfWeek) {
            return $patient->next_delivery_date && 
                   $patient->next_delivery_date->between($startOfWeek, $endOfWeek);
        });
        
        // Ordenar y paginar
        $allPatients = $allPatients->sortBy('next_delivery_date');
        $patients = new \Illuminate\Pagination\LengthAwarePaginator(
            $allPatients->forPage(request()->get('page', 1), 10),
            $allPatients->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('livewire.deliveries.weekly-schedule', compact('patients'));
    }
}