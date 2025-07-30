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
        $userPathologies = $user->patientPathologies()->with('pathology')->get();
        $userMedicines = PatientMedicine::whereHas('patientPathology', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['medicine', 'patientPathology.pathology'])->get();
        
        return view('users.medicines', compact('user', 'medicines', 'userPathologies', 'userMedicines'));
    }

    public function assignMedicine(Request $request, User $user)
    {
        $request->validate([
            'patient_pathology_id' => 'required|exists:patient_pathologies,id',
            'medicine_id' => 'required|exists:medicines,id',
            'dosage' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,suspended,completed',
        ]);

        // Verificar si ya existe la combinación activa
        $exists = PatientMedicine::where('patient_pathology_id', $request->patient_pathology_id)
            ->where('medicine_id', $request->medicine_id)
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            return redirect()->route('users.medicines', $user)
                ->with('error', 'Este medicamento ya está activo para esta patología.');
        }

        PatientMedicine::create($request->all());

        return redirect()->route('users.medicines', $user)->with('success', 'Medicamento asignado exitosamente.');
    }

    public function editMedicine(User $user, PatientMedicine $patientMedicine)
    {
        $medicines = Medicine::all();
        $userPathologies = $user->patientPathologies()->with('pathology')->get();
        return view('users.medicines-edit', compact('user', 'patientMedicine', 'medicines', 'userPathologies'));
    }

    public function updateMedicine(Request $request, User $user, PatientMedicine $patientMedicine)
    {
        $request->validate([
            'patient_pathology_id' => 'required|exists:patient_pathologies,id',
            'medicine_id' => 'required|exists:medicines,id',
            'dosage' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,suspended,completed',
        ]);

        // Verificar si ya existe la combinación activa (excluyendo el registro actual)
        if ($request->status === 'active') {
            $exists = PatientMedicine::where('patient_pathology_id', $request->patient_pathology_id)
                ->where('medicine_id', $request->medicine_id)
                ->where('status', 'active')
                ->where('id', '!=', $patientMedicine->id)
                ->exists();

            if ($exists) {
                return redirect()->route('users.medicines', $user)
                    ->with('error', 'Este medicamento ya está activo para esta patología.');
            }
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