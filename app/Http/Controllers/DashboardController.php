<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', true)->count();
        $inactiveUsers = User::where('status', false)->count();

        $maleUsers = User::where('gender', 'masculino')->count();
        $femaleUsers = User::where('gender', 'femenino')->count();

        // Datos para gráfico de entregas
        $deliveryData = \App\Models\MedicineDelivery::with('deliveryPatients')
            ->orderBy('start_date')
            ->get();
        
        $deliveryLabels = [];
        $deliveryUserCounts = [];
        
        foreach ($deliveryData as $delivery) {
            $deliveryLabels[] = $delivery->name;
            $deliveryUserCounts[] = $delivery->deliveryPatients->count();
        }
        
        // Si no hay datos, usar datos de ejemplo
        if (empty($deliveryLabels)) {
            $deliveryLabels = ['Sin entregas'];
            $deliveryUserCounts = [0];
        }

        // Datos para gráfico de usuarios por municipio
        $municipalityData = User::selectRaw('municipality_id, COUNT(*) as user_count')
            ->groupBy('municipality_id')
            ->orderBy('user_count', 'desc')
            ->with('municipality.department')
            ->get();
        
        $municipalityLabels = [];
        $municipalityCounts = [];
        $departmentLabels = [];
        $backgroundColors = [];
        
        // Generar colores únicos para cada municipio
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'];
        
        foreach ($municipalityData as $index => $data) {
            $municipalityLabels[] = $data->municipality ? $data->municipality->name : 'Sin municipio';
            $municipalityCounts[] = $data->user_count;
            $departmentLabels[] = $data->municipality && $data->municipality->department ? $data->municipality->department->name : 'Sin departamento';
            $backgroundColors[] = $colors[$index % count($colors)];
        }
        
        // Si no hay datos, usar datos de ejemplo
        if (empty($municipalityLabels)) {
            $municipalityLabels = ['Sin datos'];
            $municipalityCounts = [0];
            $departmentLabels = ['Sin departamento'];
            $backgroundColors = ['#8B5CF6'];
        }

        // Top 5 patologías más comunes
        $topPathologies = \App\Models\PatientPathology::with('pathology')
            ->selectRaw('pathology_id, COUNT(*) as usage_count')
            ->groupBy('pathology_id')
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();

        // Top 5 medicamentos más utilizados en entregas
        $topMedicines = \App\Models\DeliveryMedicine::join('patient_medicines', 'delivery_medicines.patient_medicine_id', '=', 'patient_medicines.id')
            ->join('medicines', 'patient_medicines.medicine_id', '=', 'medicines.id')
            ->selectRaw('medicines.name, COUNT(*) as usage_count')
            ->where('delivery_medicines.included', true)
            ->groupBy('medicines.id', 'medicines.name')
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();





        $chart = app()->chartjs
            ->name('usersChart')
            ->type('doughnut')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Usuarios Activos', 'Usuarios Inactivos'])
            ->datasets([
                [
                    'label' => 'Usuarios',
                    'backgroundColor' => ['#10B981', '#EF4444'],
                    'data' => [$activeUsers, $inactiveUsers]
                ]
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'position' => 'bottom'
                ]
            ]);

        $genderChart = app()->chartjs
            ->name('genderChart')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Masculino', 'Femenino'])
            ->datasets([
                [
                    'label' => 'Usuarios por Género',
                    'backgroundColor' => ['#3B82F6', '#EC4899'],
                    'data' => [$maleUsers, $femaleUsers]
                ]
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'position' => 'bottom'
                ]
            ]);

        $deliveryChart = app()->chartjs
            ->name('deliveryChart')
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels($deliveryLabels)
            ->datasets([
                [
                    'label' => 'Usuarios por Entrega',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => '#FF6384',
                    'data' => $deliveryUserCounts,
                    'fill' => false
                ]
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'position' => 'top'
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
                    ]
                ]
            ]);

        $municipalityChart = app()->chartjs
            ->name('municipalityChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($municipalityLabels)
            ->datasets([
                [
                    'label' => 'Usuarios',
                    'backgroundColor' => $backgroundColors,
                    'data' => $municipalityCounts,
                    'departmentLabels' => $departmentLabels
                ]
            ])
            ->options([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => true
                ],
                'scales' => [
                    'yAxes' => [[
                        'ticks' => [
                            'beginAtZero' => true,
                        ]
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

        return view('dashboard', compact('chart', 'genderChart', 'deliveryChart', 'municipalityChart', 'totalUsers','maleUsers', 'femaleUsers', 'activeUsers', 'inactiveUsers', 'topMedicines', 'topPathologies'));
    }
}