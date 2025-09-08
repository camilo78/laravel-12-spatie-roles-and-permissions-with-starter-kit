<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Department;
use App\Models\Municipality;
use App\Models\Locality;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Mapear encabezados en español a campos internos
        $mappedRow = [
            'name' => $row['nombre'] ?? $row['name'] ?? null,
            'email' => $row['correo_electronico'] ?? $row['email'] ?? null,
            'dni' => $row['dni'] ?? null,
            'phone' => $row['telefono'] ?? $row['phone'] ?? null,
            'address' => $row['direccion'] ?? $row['address'] ?? null,
            'department' => $row['departamento'] ?? $row['department'] ?? null,
            'municipality' => $row['municipio'] ?? $row['municipality'] ?? null,
            'locality' => $row['localidad'] ?? $row['locality'] ?? null,
            'gender' => $row['genero'] ?? $row['gender'] ?? null,
            'admission_date' => $row['fecha_de_ingreso'] ?? $row['admission_date'] ?? null,
            'password' => $row['contrasena'] ?? $row['password'] ?? null,
        ];
        
        // Buscar IDs por nombres
        $department = Department::where('name', $mappedRow['department'])->first();
        $municipality = Municipality::where('name', $mappedRow['municipality'])->first();
        $locality = Locality::where('name', $mappedRow['locality'])->first();
        
        // Convertir fecha y agregar hora 00:00
        $admissionDate = null;
        if (!empty($mappedRow['admission_date'])) {
            $admissionDate = Carbon::createFromFormat('d/m/Y', $mappedRow['admission_date'])->startOfDay();
        }
        
        $user = User::create([
            'name' => $mappedRow['name'],
            'email' => $mappedRow['email'],
            'dni' => $mappedRow['dni'],
            'phone' => $mappedRow['phone'],
            'address' => $mappedRow['address'],
            'department_id' => $department?->id,
            'municipality_id' => $municipality?->id,
            'locality_id' => $locality?->id,
            'gender' => $mappedRow['gender'],
            'status' => 1, // Todos los usuarios activos
            'admission_date' => $admissionDate,
            'password' => Hash::make($mappedRow['password']),
        ]);
        
        $user->assignRole('User');
        
        return $user;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'correo_electronico' => 'nullable|email|unique:users,email',
            'dni' => 'required|string|max:13|unique:users,dni',
            'direccion' => 'required|string|max:255',
            'departamento' => 'required|string|exists:departments,name',
            'municipio' => 'required|string|exists:municipalities,name',
            'localidad' => 'required|string|exists:localities,name',
            'genero' => 'nullable|string|in:Masculino,Femenino,masculino,femenino',
            'fecha_de_ingreso' => 'required|string',
            'contrasena' => 'required|string|min:6',
        ];
    }
}