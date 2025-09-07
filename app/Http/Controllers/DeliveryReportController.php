<?php

namespace App\Http\Controllers;

use App\Models\MedicineDelivery;
use App\Models\DeliveryPatient;
use App\Models\SystemConfiguration;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Exception;

class DeliveryReportController extends Controller
{
    public function generateReport($deliveryId)
    {
        try {
        $delivery = MedicineDelivery::with([
            'deliveryPatients' => fn($query) => $query->where('state', 'entregada'),
            'deliveryPatients.user.department',
            'deliveryPatients.user.municipality', 
            'deliveryPatients.user.locality',
            'deliveryPatients.user.patientPathologies.pathology',
            'deliveryPatients.deliveryMedicines' => fn($query) => 
                $query->whereHas('patientMedicine', fn($pm) => $pm->where('status', 'active')),
            'deliveryPatients.deliveryMedicines.patientMedicine.medicine'
        ])->findOrFail($deliveryId);

        $deliveredPatients = $delivery->deliveryPatients;
        
        // Statistics
        $totalPatients = $deliveredPatients->count();
        $malePatients = $deliveredPatients->filter(fn($dp) => $dp->user->gender === 'Masculino')->count();
        $femalePatients = $deliveredPatients->filter(fn($dp) => $dp->user->gender === 'Femenino')->count();

        // Location statistics
        $departments = $deliveredPatients->groupBy(fn($patient) => 
            $patient->user->department->name ?? 'No especificado'
        )->map(fn($group) => $group->count());
        
        $municipalities = $deliveredPatients->groupBy(fn($patient) => 
            $patient->user->municipality->name ?? 'No especificado'
        )->map(fn($group) => $group->count());

        // Medicines delivered
        $medicines = $deliveredPatients->flatMap(fn($patient) => 
            $patient->deliveryMedicines->where('included', true)
        )->groupBy('patientMedicine.medicine.generic_name')
        ->map(fn($group) => [
            'name' => $group->first()->patientMedicine->medicine->generic_name,
            'presentation' => $group->first()->patientMedicine->medicine->presentation ?? 'N/A',
            'quantity' => $group->sum('patientMedicine.quantity')
        ])->sortByDesc('quantity');

        // Pathologies
        $pathologies = $deliveredPatients->flatMap(fn($patient) => 
            $patient->user->patientPathologies->pluck('pathology')
        )->groupBy('clave')
        ->map(fn($group) => [
            'clave' => $group->first()->clave,
            'descripcion' => $group->first()->descripcion,
            'count' => $group->count()
        ])->sortByDesc('count');

        // Pacientes NO entregados
        $notDeliveredPatients = DeliveryPatient::where('medicine_delivery_id', $deliveryId)
            ->where('state', 'no_entregada')
            ->with(['user', 'deliveryMedicines.patientMedicine.medicine'])
            ->get();

        // Get configuration from system
        $config = SystemConfiguration::first();
        $appLogo = $config->app_logo ?? public_path('img/salud.png') ; 
        $hospitalLogo = $config->hospital_logo ?? public_path('img/hga.png');
        $programManager = $config->program_manager ?? 'Lic. Sandra Patricia Nuñez Hernández';
        $hospitalName = $config->hospital_name ?? 'Hospital General Atlántida';
        $programName = $config->program_name ?? 'Programa de Entrega de Medicamentos en Casa';

        $pdf = PDF::loadView('reports.delivery', compact(
            'delivery', 'totalPatients', 'malePatients', 'femalePatients',
            'departments', 'municipalities', 'medicines', 'pathologies', 'notDeliveredPatients',
            'programManager', 'hospitalName', 'programName', 'appLogo', 'hospitalLogo'
        ));

            $filename = 'Entrega_' . str_replace(' ', '_', $delivery->name) . '.pdf';
            
            return response()->json([
                'success' => true,
                'pdf' => base64_encode($pdf->output()),
                'filename' => $filename
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }
}