<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        // Puedes dejar un array vacío para que solo se genere la fila de encabezados
        // o incluir un registro de ejemplo para que el usuario vea el formato
        return [
            ['Juan Pérez', 'juan@example.com', '12345678-9', '7890-1234', 'Calle Principal #123', '1', '1', '1', 'Masculino', '1', 'password123'],
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
            'password',
        ];
    }
}