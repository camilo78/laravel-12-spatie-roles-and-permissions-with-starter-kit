<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['name'],
            'email' => $row['email'] ?? null,
            'dni' => $row['dni'],
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'],
            'department_id' => $row['department_id'],
            'municipality_id' => $row['municipality_id'],
            'locality_id' => $row['locality_id'],
            'gender' => $row['gender'] ?? null,
            'status' => $row['status'] ?? true,
            'password' => Hash::make($row['password']),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'dni' => 'required|string|max:13|unique:users,dni',
            'address' => 'required|string|max:255',
            'department_id' => 'required|integer',
            'municipality_id' => 'required|integer',
            'locality_id' => 'required|integer',
            'gender' => 'nullable|string|in:Masculino,Femenino',
            'password' => 'required|string|min:6',
        ];
    }
}