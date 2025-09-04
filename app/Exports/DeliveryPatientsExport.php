<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DeliveryPatientsExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
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
            'Ubicación'
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->name,
            $patient->dni,
            $patient->phone ?? 'No especificado',
            ($patient->municipality->name ?? 'N/A') . ', ' . ($patient->locality->name ?? 'N/A')
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER, // DNI como número sin decimales
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
        ];
    }
}