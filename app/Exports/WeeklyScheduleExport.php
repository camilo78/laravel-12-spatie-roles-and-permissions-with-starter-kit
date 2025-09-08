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
            'Correo Electrónico',
            'DNI',
            'Teléfono',
            'Dirección',
            'Departamento',
            'Municipio',
            'Localidad',
            'Género',
            'Fecha de Ingreso'
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->name,
            $patient->email,
            $patient->dni,
            $patient->phone ?? '',
            $patient->address ?? '',
            $patient->department->name ?? '',
            $patient->municipality->name ?? '',
            $patient->locality->name ?? '',
            $patient->gender,
            $patient->admission_date ? $patient->admission_date->format('d/m/Y') : '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // Nombre
            'B' => NumberFormat::FORMAT_TEXT, // Correo
            'C' => NumberFormat::FORMAT_NUMBER, // DNI
            'D' => NumberFormat::FORMAT_NUMBER, // Teléfono
            'E' => NumberFormat::FORMAT_TEXT, // Dirección
            'F' => NumberFormat::FORMAT_TEXT, // Departamento
            'G' => NumberFormat::FORMAT_TEXT, // Municipio
            'H' => NumberFormat::FORMAT_TEXT, // Localidad
            'I' => NumberFormat::FORMAT_TEXT, // Género
            'J' => NumberFormat::FORMAT_TEXT, // Fecha de Ingreso
        ];
    }
}