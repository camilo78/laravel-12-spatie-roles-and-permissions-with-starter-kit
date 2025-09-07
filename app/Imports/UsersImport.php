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
        // Buscar IDs por nombres
        $department = Department::where('name', $row['department'])->first();
        $municipality = Municipality::where('name', $row['municipality'])->first();
        $locality = Locality::where('name', $row['locality'])->first();
        
        // Convertir status de texto a boolean
        $status = strtolower($row['status']) === 'true' || $row['status'] === '1' || $row['status'] === 1;
        
        // Convertir fecha y agregar hora 00:00
        $admissionDate = null;
        if (!empty($row['admission_date'])) {
            $admissionDate = Carbon::createFromFormat('d/m/Y', $row['admission_date'])->startOfDay();
        }
        
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'] ?? null,
            'dni' => $row['dni'],
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'],
            'department_id' => $department?->id,
            'municipality_id' => $municipality?->id,
            'locality_id' => $locality?->id,
            'gender' => $row['gender'] ?? null,
            'status' => $status,
            'admission_date' => $admissionDate,
            'password' => Hash::make($row['password']),
        ]);
        
        $user->assignRole('User');
        
        return $user;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'dni' => 'required|string|max:13|unique:users,dni',
            'address' => 'required|string|max:255',
            'department' => 'required|string|exists:departments,name',
            'municipality' => 'required|string|exists:municipalities,name',
            'locality' => 'required|string|exists:localities,name',
            'gender' => 'nullable|string|in:Masculino,Femenino,masculino,femenino',
            'status' => 'required|string|in:true,false,1,0',
            'admission_date' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }
}