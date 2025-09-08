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
    protected $users;

    public function __construct($users = null)
    {
        $this->users = $users;
    }

    public function collection(): Collection
    {
        if ($this->users) {
            return $this->users->map(function ($user) {
                return [
                    $user->name,
                    $user->email,
                    $user->dni,
                    $user->phone,
                    $user->address,
                    $user->department->name ?? '',
                    $user->municipality->name ?? '',
                    $user->locality->name ?? '',
                    $user->gender,
                    $user->departmental_delivery ? 'Sí' : 'No',
                    $user->admission_date ? $user->admission_date->format('d/m/Y') : '',
                ];
            });
        }

        return User::with(['department', 'municipality', 'locality'])
            ->where('status', 1)
            ->get()
            ->map(function ($user) {
                return [
                    $user->name,
                    $user->email,
                    $user->dni,
                    $user->phone,
                    $user->address,
                    $user->department->name ?? '',
                    $user->municipality->name ?? '',
                    $user->locality->name ?? '',
                    $user->gender,
                    $user->departmental_delivery ? 'Sí' : 'No',
                    $user->admission_date ? $user->admission_date->format('d/m/Y') : '',
                ];
            });
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
            'Fecha de Ingreso'
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
        ];
    }
}