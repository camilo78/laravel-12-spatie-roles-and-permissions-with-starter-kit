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
            ['name' => 'Entrega Enero 2024', 'start_date' => '2024-01-15', 'end_date' => '2024-01-30', 'users' => 45],
            ['name' => 'Entrega Febrero 2024', 'start_date' => '2024-02-15', 'end_date' => '2024-02-28', 'users' => 67],
            ['name' => 'Entrega Marzo 2024', 'start_date' => '2024-03-15', 'end_date' => '2024-03-30', 'users' => 23],
            ['name' => 'Entrega Abril 2024', 'start_date' => '2024-04-15', 'end_date' => '2024-04-30', 'users' => 89],
            ['name' => 'Entrega Mayo 2024', 'start_date' => '2024-05-15', 'end_date' => '2024-05-31', 'users' => 34],
            ['name' => 'Entrega Junio 2024', 'start_date' => '2024-06-15', 'end_date' => '2024-06-30', 'users' => 78],
            ['name' => 'Entrega Julio 2024', 'start_date' => '2024-07-15', 'end_date' => '2024-07-31', 'users' => 56],
            ['name' => 'Entrega Agosto 2024', 'start_date' => '2024-08-15', 'end_date' => '2024-08-31', 'users' => 92],
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