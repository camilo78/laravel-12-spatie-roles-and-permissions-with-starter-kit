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
                '12345678',
                '987654321',
                'Calle 123 #45-67',
                '1',
                '1',
                '1',
                'masculino',
                '1',
                '2024-01-15'
            ]
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
            'admission_date'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
}