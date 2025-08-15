<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class UsersExport implements FromCollection, WithHeadings
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
            'status'
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
            'status'
        ];
    }
}