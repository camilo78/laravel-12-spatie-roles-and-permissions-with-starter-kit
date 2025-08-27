<?php

namespace App\Livewire\Deliveries;

use App\Models\User;
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

    public function render()
    {
        $startOfWeek = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfWeek();
        $endOfWeek = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now()->endOfWeek();

        $patients = User::query()
            ->select([
                'users.*',
                DB::raw('(
                    DATE_ADD(
                        admission_date, 
                        INTERVAL TIMESTAMPDIFF(MONTH, admission_date, CURDATE()) MONTH
                    )
                ) as next_delivery_date')
            ])
            ->whereNotNull('admission_date')
            ->where('status', true)
            ->with(['department', 'municipality', 'locality'])
            ->whereRaw('
                DATE_ADD(
                    admission_date, 
                    INTERVAL TIMESTAMPDIFF(MONTH, admission_date, CURDATE()) MONTH
                ) BETWEEN ? AND ?
            ', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('dni', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('next_delivery_date', 'asc')
            ->paginate(10);
            
        $weekStart = $startOfWeek->format('d/m/Y');
        $weekEnd = $endOfWeek->format('d/m/Y');
        
        return view('livewire.deliveries.weekly-schedule', compact('patients', 'weekStart', 'weekEnd'));
    }
}