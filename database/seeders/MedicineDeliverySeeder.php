<?php

namespace Database\Seeders;

use App\Models\MedicineDelivery;
use App\Models\DeliveryPatient;
use App\Models\DeliveryMedicine;
use App\Models\User;
use App\Models\PatientMedicine;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MedicineDeliverySeeder extends Seeder
{
    public function run(): void
    {
        $deliveries = [
            ['name' => 'Entrega Enero 2025', 'start_date' => '2025-01-15', 'end_date' => '2025-01-30', 'users' => 45],
            ['name' => 'Entrega Febrero 2025', 'start_date' => '2025-02-15', 'end_date' => '2025-02-28', 'users' => 67],
            ['name' => 'Entrega Marzo 2025', 'start_date' => '2025-03-15', 'end_date' => '2025-03-30', 'users' => 23],
            ['name' => 'Entrega Abril 2025', 'start_date' => '2025-04-15', 'end_date' => '2025-04-30', 'users' => 89],
            ['name' => 'Entrega Mayo 2025', 'start_date' => '2025-05-15', 'end_date' => '2025-05-31', 'users' => 34],
            ['name' => 'Entrega Junio 2025', 'start_date' => '2025-06-15', 'end_date' => '2025-06-30', 'users' => 78],
            ['name' => 'Entrega Julio 2025', 'start_date' => '2025-07-15', 'end_date' => '2025-07-31', 'users' => 56],
            ['name' => 'Entrega Agosto 2025', 'start_date' => '2025-08-15', 'end_date' => '2025-08-31', 'users' => 92],
            ['name' => 'Entrega Septiembre 2025', 'start_date' => '2025-09-15', 'end_date' => '2025-09-30', 'users' => 41],
            ['name' => 'Entrega Octubre 2025', 'start_date' => '2025-10-15', 'end_date' => '2025-10-31', 'users' => 63],
            ['name' => 'Entrega Noviembre 2025', 'start_date' => '2025-11-15', 'end_date' => '2025-11-30', 'users' => 55],
            ['name' => 'Entrega Diciembre 2025', 'start_date' => '2025-12-15', 'end_date' => '2025-12-30', 'users' => 72],
        ];

        foreach ($deliveries as $deliveryData) {
            $delivery = MedicineDelivery::create([
                'name' => $deliveryData['name'],
                'start_date' => Carbon::parse($deliveryData['start_date']),
                'end_date' => Carbon::parse($deliveryData['end_date']),
            ]);

            // Obtener usuarios aleatorios
            $users = User::inRandomOrder()->limit($deliveryData['users'])->get();

            foreach ($users as $user) {
                $deliveryPatient = DeliveryPatient::create([
                    'medicine_delivery_id' => $delivery->id,
                    'user_id' => $user->id,
                    'included' => true,
                ]);

                // Obtener medicamentos del usuario
                $patientMedicines = PatientMedicine::where('user_id', $user->id)
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