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
        // Puedes dejar un array vacío para que solo se genere la fila de encabezados
        // o incluir un registro de ejemplo para que el usuario vea el formato
        return [
            ['Juan Pérez', 'juan@example.com', '12345678-9', '7890-1234', 'Calle Principal #123', '1', '1', '1', 'Masculino', '1', '2024-01-15', 'password123'],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'dni',
            'phone',
            'address',
            'department_id',
            'municipality_id',
            'locality_id',
            'gender',
            'status',
            'admission_date',
            'password',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER, // DNI como número sin decimales
            'D' => NumberFormat::FORMAT_NUMBER, // Teléfono como número sin decimales
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_TEXT,
            'L' => NumberFormat::FORMAT_TEXT,
        ];
    }
}