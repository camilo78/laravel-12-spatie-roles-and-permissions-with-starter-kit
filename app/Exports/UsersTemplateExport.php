<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UsersTemplateExport implements FromArray, WithHeadings, WithColumnFormatting
{
    public function array(): array
    {
        return [
            [
                'Juan Pérez',
                'juan@email.com',
                '0101198509263',
                '98765432',
                'Calle 123 #45-67',
                'Atlántida',
                'La Ceiba',
                'Col. Las Tres Posas',
                'masculino',
                'No',
                '15/01/2024',
                'password123'
            ]
        ];
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
            'Entrega Departamental',
            'Fecha de Ingreso',
            'Contraseña'
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
            'J' => NumberFormat::FORMAT_TEXT, // Entrega Departamental
            'K' => NumberFormat::FORMAT_TEXT, // Fecha de Ingreso
            'L' => NumberFormat::FORMAT_TEXT, // Contraseña
        ];
    }
}