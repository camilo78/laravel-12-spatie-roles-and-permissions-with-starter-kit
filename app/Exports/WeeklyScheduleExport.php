<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class WeeklyScheduleExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    protected $patients;

    public function __construct($patients)
    {
        $this->patients = $patients;
    }

    public function collection()
    {
        return $this->patients;
    }

    public function headings(): array
    {
        return [
            'Paciente',
            'DNI',
            'Teléfono',
            'Ubicación',
            'Fecha Ingreso',
            'Estado',
            'Próxima Entrega',
            'Medicamentos Activos'
        ];
    }

    public function map($patient): array
    {
        $deliveryPatient = $patient->deliveryPatients->first();
        $activeMedicines = $patient->patientMedicines->where('status', 'active')->count();
        
        return [
            $patient->name,
            $patient->dni,
            $patient->phone ?? 'No especificado',
            ($patient->municipality->name ?? 'N/A') . ', ' . ($patient->locality->name ?? 'N/A'),
            $patient->admission_date->format('d/m/Y'),
            $deliveryPatient ? ucfirst(str_replace('_', ' ', $deliveryPatient->state)) : 'Sin entrega',
            $patient->next_delivery_date->format('d/m/Y'),
            $activeMedicines
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER, // DNI como número sin decimales
            'C' => NumberFormat::FORMAT_NUMBER, // Teléfono como número sin decimales
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Fecha Ingreso como fecha
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY, // Próxima Entrega como fecha
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}