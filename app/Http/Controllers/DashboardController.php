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
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom'
                    ],
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold'
                        ]
                    ]
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
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom'
                    ],
                    'datalabels' => [
                        'display' => true,
                        'color' => 'white',
                        'font' => [
                            'weight' => 'bold'
                        ]
                    ]
                ]
            ]);

        return view('dashboard', compact('chart', 'genderChart', 'totalUsers','maleUsers', 'femaleUsers'));
    }
}