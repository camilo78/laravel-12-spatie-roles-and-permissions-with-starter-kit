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
                'true',
                '15/01/2024',
                'password123'
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
            'department',
            'municipality',
            'locality',
            'gender',
            'status',
            'admission_date',
            'password'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // name
            'B' => NumberFormat::FORMAT_TEXT, // email
            'C' => NumberFormat::FORMAT_NUMBER, // dni
            'D' => NumberFormat::FORMAT_NUMBER, // phone
            'E' => NumberFormat::FORMAT_TEXT, // address
            'F' => NumberFormat::FORMAT_TEXT, // department
            'G' => NumberFormat::FORMAT_TEXT, // municipality
            'H' => NumberFormat::FORMAT_TEXT, // locality
            'I' => NumberFormat::FORMAT_TEXT, // gender
            'J' => NumberFormat::FORMAT_TEXT, // status
            'K' => NumberFormat::FORMAT_TEXT, // admission_date
            'L' => NumberFormat::FORMAT_TEXT, // password
        ];
    }
}