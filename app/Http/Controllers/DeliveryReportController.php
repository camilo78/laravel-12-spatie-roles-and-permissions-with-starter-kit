<?php

namespace App\Http\Controllers;

use App\Models\MedicineDelivery;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DeliveryReportController extends Controller
{
    public function generateReport($deliveryId)
    {
        $delivery = MedicineDelivery::with([
            'deliveryPatients' => function($query) {
                $query->whereHas('deliveryMedicines', function($q) {
                    $q->where('included', true)
                      ->whereHas('patientMedicine', function($pm) {
                          $pm->where('status', 'active');
                      });
                });
            },
            'deliveryPatients.user.department',
            'deliveryPatients.user.municipality', 
            'deliveryPatients.user.locality',
            'deliveryPatients.user.patientPathologies.pathology',
            'deliveryPatients.deliveryMedicines' => function($query) {
                $query->where('included', true)
                      ->whereHas('patientMedicine', function($pm) {
                          $pm->where('status', 'active');
                      });
            },
            'deliveryPatients.deliveryMedicines.patientMedicine.medicine'
        ])->findOrFail($deliveryId);

        // Statistics
        $totalPatients = $delivery->deliveryPatients->count();
        $malePatients = $delivery->deliveryPatients->filter(fn($dp) => $dp->user->gender === 'Masculino')->count();
        $femalePatients = $delivery->deliveryPatients->filter(fn($dp) => $dp->user->gender === 'Femenino')->count();
        
        // Delivery statistics
        $patientsWithDeliveries = $delivery->deliveryPatients->filter(function($patient) {
            return $patient->deliveryMedicines->where('included', true)->count() > 0;
        })->count();
        $patientsWithoutDeliveries = $delivery->deliveryPatients->filter(function($patient) {
            return $patient->deliveryMedicines->where('included', false)->count() > 0;
        })->count();

        // Location statistics
        $departments = $delivery->deliveryPatients->groupBy(function($patient) {
            return $patient->user->department->name ?? 'No especificado';
        })->map(function($group) {
            return $group->count();
        });
        
        $municipalities = $delivery->deliveryPatients->groupBy(function($patient) {
            return $patient->user->municipality->name ?? 'No especificado';
        })->map(function($group) {
            return $group->count();
        });
        
        $totalMunicipalities = $municipalities->sum();
        
        $localities = $delivery->deliveryPatients->groupBy(function($patient) {
            return $patient->user->locality->name ?? 'No especificado';
        })->map(function($group) {
            return $group->count();
        });

        // Medicines delivered
        $medicines = collect();
        foreach ($delivery->deliveryPatients as $patient) {
            foreach ($patient->deliveryMedicines->where('included', true) as $deliveryMedicine) {
                $medicines->push($deliveryMedicine);
            }
        }
        $medicines = $medicines->groupBy('patientMedicine.medicine.generic_name')
            ->map(fn($group) => [
                'name' => $group->first()->patientMedicine->medicine->generic_name,
                'presentation' => $group->first()->patientMedicine->medicine->presentation ?? 'N/A',
                'quantity' => $group->sum('patientMedicine.quantity')
            ])
            ->sortByDesc('quantity');

        // Pathologies
        $pathologies = collect();
        foreach ($delivery->deliveryPatients as $patient) {
            foreach ($patient->user->patientPathologies as $pathology) {
                $pathologies->push($pathology->pathology);
            }
        }
        $pathologies = $pathologies->groupBy('clave')->map(fn($group) => [
            'clave' => $group->first()->clave,
            'descripcion' => $group->first()->descripcion,
            'count' => $group->count()
        ])->sortByDesc('count');

        // Medicines NOT delivered with reasons
        $notDeliveredMedicines = \App\Models\DeliveryPatient::where('medicine_delivery_id', $deliveryId)
            ->with(['deliveryMedicines' => function($query) {
                $query->where('included', false)->with('patientMedicine.medicine');
            }, 'user'])
            ->get()
            ->flatMap(function($patient) {
                return $patient->deliveryMedicines->map(function($deliveryMedicine) use ($patient) {
                    return [
                        'patient_name' => $patient->user->name,
                        'patient_dni' => $patient->user->dni,
                        'medicine_name' => $deliveryMedicine->patientMedicine->medicine->generic_name,
                        'presentation' => $deliveryMedicine->patientMedicine->medicine->presentation ?? 'N/A',
                        'quantity' => $deliveryMedicine->patientMedicine->quantity,
                        'reason' => $deliveryMedicine->observations ?? 'Sin motivo especificado'
                    ];
                });
            });

        // Calculate estimated pages
        $totalRows = $delivery->deliveryPatients->count() + 
                    $departments->count() + 
                    $municipalities->count() + 
                    $medicines->count() + 
                    $pathologies->count() + 
                    $delivery->deliveryPatients->sum(function($patient) { return $patient->deliveryMedicines->count(); });
        $estimatedPages = max(1, ceil($totalRows / 25)); // ~25 rows per page

        $pdf = PDF::loadView('reports.delivery', compact(
            'delivery', 'totalPatients', 'malePatients', 'femalePatients',
            'departments', 'municipalities', 'localities', 'medicines', 'pathologies', 'totalMunicipalities', 'notDeliveredMedicines',
            'patientsWithDeliveries', 'patientsWithoutDeliveries', 'estimatedPages'
        ));

        return $pdf->download('Entrega_' . str_replace(' ', '_', $delivery->name) . '.pdf');
    }
}