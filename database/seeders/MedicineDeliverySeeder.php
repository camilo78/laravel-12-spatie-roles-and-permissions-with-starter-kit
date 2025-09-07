<?php

namespace Database\Seeders;

use App\Models\MedicineDelivery;
use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use App\Models\User;
use App\Models\PatientMedicine;
use App\Models\SystemConfiguration;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MedicineDeliverySeeder extends Seeder
{
    public function run(): void
    {
        // Obtener configuraciones del sistema
        $config = SystemConfiguration::first();
        $firstDeliveryDays = $config->first_delivery_days ?? 30;
        $subsequentDeliveryDays = $config->subsequent_delivery_days ?? 120;
        
        // Obtener usuarios con medicamentos activos ordenados por fecha de admisión
        $users = User::whereHas('patientMedicines', function($query) {
            $query->where('status', 'active');
        })->orderBy('admission_date')->get();
        
        // Crear entregas cronológicamente por mes
        $deliveriesByMonth = [];
        
        foreach ($users as $user) {
            $admissionDate = Carbon::parse($user->admission_date);
            
            // Primera entrega: 30 días después del ingreso
            $firstDeliveryDate = $admissionDate->copy()->addDays($firstDeliveryDays);
            // Ajustar al viernes si cae en fin de semana
            if ($firstDeliveryDate->isSaturday()) {
                $firstDeliveryDate->subDay();
            } elseif ($firstDeliveryDate->isSunday()) {
                $firstDeliveryDate->subDays(2);
            }
            
            // Generar entregas hasta diciembre 2025
            $endDate = Carbon::parse('2025-12-31');
            $deliveryCount = 1;
            $currentDeliveryDate = $firstDeliveryDate->copy();
            
            while ($currentDeliveryDate->lte($endDate)) {
                $monthYear = $currentDeliveryDate->format('Y-m');
                $deliveryName = "Entrega " . $currentDeliveryDate->locale('es')->isoFormat('MMMM YYYY');
                
                // Agrupar por mes para crear entregas cronológicamente
                if (!isset($deliveriesByMonth[$monthYear])) {
                    $deliveriesByMonth[$monthYear] = [
                        'name' => $deliveryName,
                        'start_date' => $currentDeliveryDate->copy()->startOfMonth(),
                        'end_date' => $currentDeliveryDate->copy()->endOfMonth(),
                        'patients' => []
                    ];
                }
                
                // Agregar paciente a esta entrega
                $deliveriesByMonth[$monthYear]['patients'][] = $user->id;
                
                // Calcular siguiente entrega
                if ($deliveryCount === 1) {
                    $currentDeliveryDate->addDays($subsequentDeliveryDays);
                } else {
                    $currentDeliveryDate->addDays($subsequentDeliveryDays);
                }
                
                // Ajustar al viernes si cae en fin de semana
                if ($currentDeliveryDate->isSaturday()) {
                    $currentDeliveryDate->subDay();
                } elseif ($currentDeliveryDate->isSunday()) {
                    $currentDeliveryDate->subDays(2);
                }
                
                $deliveryCount++;
            }
        }
        
        // Crear entregas cronológicamente
        ksort($deliveriesByMonth);
        
        foreach ($deliveriesByMonth as $monthData) {
            $delivery = MedicineDelivery::create([
                'name' => $monthData['name'],
                'start_date' => $monthData['start_date'],
                'end_date' => $monthData['end_date'],
            ]);
            
            // Agregar pacientes únicos a esta entrega
            $uniquePatients = array_unique($monthData['patients']);
            
            foreach ($uniquePatients as $userId) {
                $deliveryPatient = DeliveryPatient::create([
                    'medicine_delivery_id' => $delivery->id,
                    'user_id' => $userId,
                    'state' => 'programada',
                ]);
                
                // Obtener medicamentos activos del usuario
                $patientMedicines = PatientMedicine::where('user_id', $userId)
                    ->where('status', 'active')
                    ->get();
                
                foreach ($patientMedicines as $medicine) {
                    DeliveryMedicine::create([
                        'delivery_patient_id' => $deliveryPatient->id,
                        'patient_medicine_id' => $medicine->id,
                        'included' => true,
                    ]);
                }
            }
        }
    }
}