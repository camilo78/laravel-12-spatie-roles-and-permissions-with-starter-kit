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
            'Nombre',
            'DNI',
            'Teléfono',
            'Dirección',
            'Departamento',
            'Municipio',
            'Localidad'
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->name,
            $patient->dni,
            $patient->phone ?? '',
            $patient->address ?? '',
            $patient->department->name ?? '',
            $patient->municipality->name ?? '',
            $patient->locality->name ?? '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Nombre
            'B' => NumberFormat::FORMAT_NUMBER, // DNI como número sin decimales
            'C' => NumberFormat::FORMAT_NUMBER, // Teléfono como número sin decimales
            'D' => NumberFormat::FORMAT_TEXT, // Dirección
            'E' => NumberFormat::FORMAT_TEXT, // Departamento
            'F' => NumberFormat::FORMAT_TEXT, // Municipio
            'G' => NumberFormat::FORMAT_TEXT, // Localidad
        ];
    }
}