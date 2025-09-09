<?php

namespace App\Livewire\Deliveries;

use App\Models\User;
use App\Models\DeliveryPatient;
use App\Helpers\SystemConfigHelper;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class WeeklySchedule extends Component
{
    use WithPagination;
    
    public $search = '';
    public $startDate = '';
    public $endDate = '';
    public $departmentalDeliveryFilter = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->startDate = '';
        $this->endDate = '';
        $this->departmentalDeliveryFilter = null;
        $this->resetPage();
    }

    public function updatePatientState($deliveryPatientId, $state)
    {
        $deliveryPatient = DeliveryPatient::findOrFail($deliveryPatientId);
        
        // Si el estado no es 'no_entregada', limpiar las notas
        $updateData = ['state' => $state];
        if ($state !== 'no_entregada') {
            $updateData['delivery_notes'] = null;
        }
        
        $deliveryPatient->update($updateData);
    }

    public function updatePatientNotes($deliveryPatientId, $notes)
    {
        $deliveryPatient = DeliveryPatient::findOrFail($deliveryPatientId);
        $deliveryPatient->update(['delivery_notes' => $notes]);
    }

    public function exportWeeklySchedule()
    {
        // Obtener los misma datos que se muestran en la tabla
        $currentMonth = Carbon::now();
        $startDate = $this->startDate ? Carbon::parse($this->startDate) : $currentMonth->copy()->startOfMonth();
        $endDate = $this->endDate ? Carbon::parse($this->endDate) : $currentMonth->copy()->endOfMonth();

        $query = User::query()
            ->where('status', true)
            ->whereNotNull('admission_date')
            ->with(['department', 'municipality', 'locality', 'patientMedicines']);
            
        if ($this->departmentalDeliveryFilter !== null) {
            $query->where('departmental_delivery', $this->departmentalDeliveryFilter);
        }

        if ($this->search) {
            $search = trim($this->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $allUsers = $query->get();
        $filteredUsers = collect();

        foreach ($allUsers as $user) {
            $nextDelivery = $user->getNextDeliveryDate();
            if ($nextDelivery && $nextDelivery->between($startDate, $endDate)) {
                $user->next_delivery_date = $nextDelivery;
                $filteredUsers->push($user);
            }
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\WeeklyScheduleExport($filteredUsers), 
            'entregas_programadas_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPatientsPDF()
    {
        $currentMonth = Carbon::now();
        $startDate = $this->startDate ? Carbon::parse($this->startDate) : $currentMonth->copy()->startOfMonth();
        $endDate = $this->endDate ? Carbon::parse($this->endDate) : $currentMonth->copy()->endOfMonth();

        $medicineDelivery = \App\Models\MedicineDelivery::where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->first();
            
        $query = User::query()
            ->where('status', true)
            ->whereNotNull('admission_date')
            ->with(['department', 'municipality', 'locality', 'patientMedicines']);
            
        if ($medicineDelivery) {
            $query->with(['deliveryPatients' => function($q) use ($medicineDelivery) {
                $q->where('medicine_delivery_id', $medicineDelivery->id)
                  ->with(['deliveryMedicines.patientMedicine.medicine']);
            }]);
        }
            
        if ($this->departmentalDeliveryFilter !== null) {
            $query->where('departmental_delivery', $this->departmentalDeliveryFilter);
        }

        if ($this->search) {
            $search = trim($this->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $allUsers = $query->get();
        $filteredUsers = collect();

        foreach ($allUsers as $user) {
            $nextDelivery = $user->getNextDeliveryDate();
            if ($nextDelivery && $nextDelivery->between($startDate, $endDate)) {
                $filteredUsers->push($user);
            }
        }

        // Obtener configuración del sistema
        $config = \App\Models\SystemConfiguration::first();
        $appLogo = $config->app_logo ?? public_path('img/salud.png');
        $hospitalLogo = $config->hospital_logo ?? public_path('img/hga.png');
        $programManager = $config->program_manager ?? 'Lic. Sandra Patricia Nuñez Hernández';
        $hospitalName = $config->hospital_name ?? 'Hospital General Atlántida';
        $programName = $config->program_name ?? 'Programa de Entrega de Medicamentos en Casa';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.weekly-schedule-patients', [
            'patients' => $filteredUsers,
            'weekStart' => $startDate->format('d/m/Y'),
            'weekEnd' => $endDate->format('d/m/Y'),
            'appLogo' => $appLogo,
            'hospitalLogo' => $hospitalLogo,
            'programManager' => $programManager,
            'hospitalName' => $hospitalName,
            'programName' => $programName
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'pacientes_programados_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function exportReceptionForms()
    {
        $currentMonth = Carbon::now();
        $startDate = $this->startDate ? Carbon::parse($this->startDate) : $currentMonth->copy()->startOfMonth();
        $endDate = $this->endDate ? Carbon::parse($this->endDate) : $currentMonth->copy()->endOfMonth();

        $medicineDelivery = \App\Models\MedicineDelivery::where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->first();
            
        $query = User::query()
            ->where('status', true)
            ->whereNotNull('admission_date')
            ->with(['department', 'municipality', 'locality', 'patientMedicines']);
            
        if ($medicineDelivery) {
            $query->with(['deliveryPatients' => function($q) use ($medicineDelivery) {
                $q->where('medicine_delivery_id', $medicineDelivery->id)
                  ->with(['deliveryMedicines.patientMedicine.medicine']);
            }]);
        }
            
        if ($this->departmentalDeliveryFilter !== null) {
            $query->where('departmental_delivery', $this->departmentalDeliveryFilter);
        }

        if ($this->search) {
            $search = trim($this->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $allUsers = $query->get();
        $filteredUsers = collect();

        foreach ($allUsers as $user) {
            $nextDelivery = $user->getNextDeliveryDate();
            if ($nextDelivery && $nextDelivery->between($startDate, $endDate)) {
                $filteredUsers->push($user);
            }
        }

        // Obtener configuración del sistema
        $config = \App\Models\SystemConfiguration::first();
        $appLogo = $config->app_logo ?? public_path('img/salud.png');
        $hospitalLogo = $config->hospital_logo ?? public_path('img/hga.png');
        $programManager = $config->program_manager ?? 'Lic. Sandra Patricia Nuñez Hernández';
        $hospitalName = $config->hospital_name ?? 'Hospital General Atlántida';
        $programName = $config->program_name ?? 'Programa de Entrega de Medicamentos en Casa';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.reception-forms', [
            'patients' => $filteredUsers,
            'appLogo' => $appLogo,
            'hospitalLogo' => $hospitalLogo,
            'programManager' => $programManager,
            'hospitalName' => $hospitalName,
            'programName' => $programName
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'formatos_recepcion_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function render()
    {
        // Fechas del mes actual
        $currentMonth = Carbon::now();
        $startDate = $this->startDate ? Carbon::parse($this->startDate) : $currentMonth->copy()->startOfMonth();
        $endDate = $this->endDate ? Carbon::parse($this->endDate) : $currentMonth->copy()->endOfMonth();

        // Buscar MedicineDelivery que coincida con el rango de fechas
        $medicineDelivery = \App\Models\MedicineDelivery::where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->first();
            
        // Obtener usuarios activos con admission_date
        $query = User::query()
            ->where('status', true)
            ->whereNotNull('admission_date')
            ->with(['department', 'municipality', 'locality', 'patientMedicines']);
            
        // Filtro de entrega departamental
        if ($this->departmentalDeliveryFilter !== null) {
            $query->where('departmental_delivery', $this->departmentalDeliveryFilter);
        }
            
        if ($medicineDelivery) {
            $query->with(['deliveryPatients' => function($q) use ($medicineDelivery) {
                $q->where('medicine_delivery_id', $medicineDelivery->id);
            }]);
        }

        // Filtro de búsqueda
        if ($this->search) {
            $search = trim($this->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Obtener todos los usuarios y filtrar por fecha de entrega
        $allUsers = $query->get();
        $filteredUsers = collect();

        foreach ($allUsers as $user) {
            $nextDelivery = $user->getNextDeliveryDate();
            
            // Solo mostrar usuarios cuya próxima entrega esté en el rango seleccionado
            if ($nextDelivery && $nextDelivery->between($startDate, $endDate)) {
                $user->next_delivery_date = $nextDelivery;
                $filteredUsers->push($user);
            }
        }

        // Paginar resultados
        $currentPage = $this->getPage();
        $perPage = 10;
        $total = $filteredUsers->count();

        if ($total > 0 && $currentPage > ceil($total / $perPage)) {
            $this->setPage(1);
            $currentPage = 1;
        }

        $items = $filteredUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $patients = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        $patients->withPath(request()->url());

        return view('livewire.deliveries.weekly-schedule', [
            'patients' => $patients,
            'weekStart' => $startDate->format('d/m/Y'),
            'weekEnd' => $endDate->format('d/m/Y'),
            'medicineDelivery' => $medicineDelivery
        ]);
    }
}