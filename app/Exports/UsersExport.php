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
            'C' => NumberFormat::FORMAT_TEXT, // Columna DNI como texto
        ];
    }
}