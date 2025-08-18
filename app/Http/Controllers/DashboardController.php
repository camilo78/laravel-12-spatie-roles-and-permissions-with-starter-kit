<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (Auth::user()->roles->contains('name', 'Admin')) {
            return $this->adminDashboard();
        }
        
        // User dashboard - no data needed, all info comes from auth()->user()
        return view('dashboard');
    }
    
    private function adminDashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', true)->count();
        $inactiveUsers = User::where('status', false)->count();
        $maleUsers = User::where('gender', 'masculino')->count();
        $femaleUsers = User::where('gender', 'femenino')->count();

        // Delivery data for chart and table with active medicines filter
        $deliveries = \App\Models\MedicineDelivery::with([
            'deliveryPatients.deliveryMedicines',
            'deliveryPatients.user'
        ])->orderBy('start_date')->get();
        
        $deliveryLabels = [];
        $deliveryUserCounts = [];
        
        foreach ($deliveries as $delivery) {
            $deliveryLabels[] = $delivery->name;
            $deliveryUserCounts[] = $delivery->deliveryPatients->count();
        }
        
        if (empty($deliveryLabels)) {
            $deliveryLabels = ['Sin entregas'];
            $deliveryUserCounts = [0];
        }

        // Municipality data for chart
        $municipalityData = User::selectRaw('municipality_id, COUNT(*) as user_count')
            ->groupBy('municipality_id')
            ->orderBy('user_count', 'desc')
            ->with('municipality.department')
            ->get();
        
        $municipalityLabels = [];
        $municipalityCounts = [];
        $departmentLabels = [];
        $backgroundColors = [];
        
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
        
        foreach ($municipalityData as $index => $data) {
            $municipalityLabels[] = $data->municipality ? $data->municipality->name : 'Sin municipio';
            $municipalityCounts[] = $data->user_count;
            $departmentLabels[] = $data->municipality && $data->municipality->department ? $data->municipality->department->name : 'Sin departamento';
            $backgroundColors[] = $colors[$index % count($colors)];
        }
        
        if (empty($municipalityLabels)) {
            $municipalityLabels = ['Sin datos'];
            $municipalityCounts = [0];
            $departmentLabels = ['Sin departamento'];
            $backgroundColors = ['#8B5CF6'];
        }

        // Top pathologies
        $topPathologies = \App\Models\PatientPathology::with('pathology')
            ->selectRaw('pathology_id, COUNT(*) as usage_count')
            ->groupBy('pathology_id')
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();

        // Top medicines
        $topMedicines = \App\Models\DeliveryMedicine::join('patient_medicines', 'delivery_medicines.patient_medicine_id', '=', 'patient_medicines.id')
            ->join('medicines', 'patient_medicines.medicine_id', '=', 'medicines.id')
            ->selectRaw('medicines.generic_name, COUNT(*) as usage_count')
            ->where('delivery_medicines.included', true)
            ->groupBy('medicines.id', 'medicines.generic_name')
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();

        // Charts
        $chart = app()->chartjs
            ->name('usersChart')
            ->type('doughnut')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Usuarios Activos', 'Usuarios Inactivos'])
            ->datasets([[
                'label' => 'Usuarios',
                'backgroundColor' => ['#10B981', '#EF4444'],
                'data' => [$activeUsers, $inactiveUsers]
            ]])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => ['position' => 'bottom']
            ]);

        $genderChart = app()->chartjs
            ->name('genderChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Masculino', 'Femenino'])
            ->datasets([[
                'label' => 'Usuarios por Género',
                'backgroundColor' => ['#3B82F6', '#EC4899'],
                'data' => [$maleUsers, $femaleUsers]
            ]])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => ['position' => 'bottom']
            ]);

        $deliveryChart = app()->chartjs
            ->name('deliveryChart')
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels($deliveryLabels)
            ->datasets([[
                'label' => 'Usuarios por Entrega',
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => '#FF6384',
                'data' => $deliveryUserCounts,
                'fill' => false
            ]])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => ['position' => 'top'],
                'scales' => ['y' => ['beginAtZero' => true]]
            ]);

        $municipalityChart = app()->chartjs
            ->name('municipalityChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($municipalityLabels)
            ->datasets([[
                'label' => 'Usuarios',
                'backgroundColor' => $backgroundColors,
                'data' => $municipalityCounts,
                'departmentLabels' => $departmentLabels
            ]])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => ['display' => true],
                'scales' => [
                    'yAxes' => [[
                        'ticks' => ['beginAtZero' => true]
                    ]]
                ],
                'tooltips' => [
                    'callbacks' => [
                        'afterLabel' => 'function(tooltipItem, data) {
                            var departmentLabels = data.datasets[0].departmentLabels;
                            return "Departamento: " + departmentLabels[tooltipItem.index];
                        }'
                    ]
                ]
            ]);

        // Patients with and without deliveries
        $patientsWithDeliveries = \App\Models\DeliveryPatient::distinct('user_id')->count('user_id');
        $patientsWithoutDeliveries = $totalUsers - $patientsWithDeliveries;

        return view('dashboard', compact(
            'chart', 'genderChart', 'deliveryChart', 'municipalityChart', 
            'totalUsers', 'maleUsers', 'femaleUsers', 'activeUsers', 'inactiveUsers', 
            'topMedicines', 'topPathologies', 'deliveries', 'patientsWithDeliveries', 'patientsWithoutDeliveries'
        ));
    }
}