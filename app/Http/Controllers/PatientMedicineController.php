<?php

namespace App\Http\Controllers;

use App\Models\PatientMedicine;
use App\Models\PatientPathology;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Http\Request;

class PatientMedicineController extends Controller
{
    public function userMedicines(User $user)
    {
        $medicines = Medicine::all();
        $userMedicines = PatientMedicine::where('user_id', $user->id)
            ->with('medicine')->get();
        
        return view('users.medicines', compact('user', 'medicines', 'userMedicines'));
    }

    public function assignMedicine(Request $request, User $user)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'dosage' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,suspended,completed',
        ]);

        // Verificar si ya existe la combinación
        $exists = PatientMedicine::where('user_id', $user->id)
            ->where('medicine_id', $request->medicine_id)
            ->exists();

        if ($exists) {
            return redirect()->route('users.medicines', $user)
                ->with('error', 'Este medicamento ya está asignado al usuario.');
        }

        PatientMedicine::create(array_merge($request->all(), ['user_id' => $user->id]));

        return redirect()->route('users.medicines', $user)->with('success', 'Medicamento asignado exitosamente.');
    }

    public function editMedicine(User $user, PatientMedicine $patientMedicine)
    {
        $medicines = Medicine::all();
        return view('users.medicines-edit', compact('user', 'patientMedicine', 'medicines'));
    }

    public function updateMedicine(Request $request, User $user, PatientMedicine $patientMedicine)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'dosage' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,suspended,completed',
        ]);

        // Verificar si ya existe la combinación (excluyendo el registro actual)
        $exists = PatientMedicine::where('user_id', $user->id)
            ->where('medicine_id', $request->medicine_id)
            ->where('id', '!=', $patientMedicine->id)
            ->exists();

        if ($exists) {
            return redirect()->route('users.medicines', $user)
                ->with('error', 'Este medicamento ya está asignado al usuario.');
        }

        $patientMedicine->update($request->all());

        return redirect()->route('users.medicines', $user)->with('success', 'Medicamento actualizado exitosamente.');
    }

    public function removeMedicine(User $user, PatientMedicine $patientMedicine)
    {
        $patientMedicine->delete();
        return redirect()->route('users.medicines', $user)->with('success', 'Medicamento removido exitosamente.');
    }
}