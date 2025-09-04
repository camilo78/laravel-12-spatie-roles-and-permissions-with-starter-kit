<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Collection;

class UsersExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    public function collection(): Collection
    {
        return User::select(
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
        )->get();
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
            'C' => NumberFormat::FORMAT_NUMBER, // DNI como número sin decimales
            'D' => NumberFormat::FORMAT_NUMBER, // Teléfono como número sin decimales
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY, // admission_date como fecha
        ];
    }
}