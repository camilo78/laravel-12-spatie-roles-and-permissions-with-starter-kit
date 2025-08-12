<?php

namespace App\Http\Controllers;

use App\Models\PatientMedicine;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de medicamentos de pacientes
 * Maneja la asignación, edición y eliminación de medicamentos para usuarios específicos
 */
class PatientMedicineController extends Controller
{
    /**
     * Mostrar página de gestión de medicamentos para un usuario específico
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function userMedicines(User $user)
    {
        // Obtener todos los medicamentos disponibles
        $medicines = Medicine::all();
        
        // Obtener medicamentos asignados al usuario con relación
        $userMedicines = PatientMedicine::where('user_id', $user->id)
            ->with('medicine')
            ->get();
        
        return view('users.medicines', compact('user', 'medicines', 'userMedicines'));
    }

    /**
     * Asignar un medicamento a un usuario
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignMedicine(Request $request, User $user)
    {
        // Validar datos de entrada
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'dosage' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,suspended,completed',
        ]);

        // Verificar si el medicamento ya está asignado al usuario
        $exists = PatientMedicine::where('user_id', $user->id)
            ->where('medicine_id', $request->medicine_id)
            ->exists();

        if ($exists) {
            return redirect()->route('users.medicines', $user)
                ->with('error', 'Este medicamento ya está asignado al usuario.');
        }

        // Crear nueva asignación de medicamento
        PatientMedicine::create(array_merge($request->all(), ['user_id' => $user->id]));

        return redirect()->route('users.medicines', $user)
            ->with('success', 'Medicamento asignado exitosamente.');
    }

    /**
     * Mostrar formulario para editar medicamento de un paciente
     * 
     * @param \App\Models\User $user
     * @param \App\Models\PatientMedicine $patientMedicine
     * @return \Illuminate\View\View
     */
    public function editMedicine(User $user, PatientMedicine $patientMedicine)
    {
        $medicines = Medicine::all();
        return view('users.medicines-edit', compact('user', 'patientMedicine', 'medicines'));
    }

    /**
     * Actualizar medicamento asignado a un paciente
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @param \App\Models\PatientMedicine $patientMedicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMedicine(Request $request, User $user, PatientMedicine $patientMedicine)
    {
        // Validar datos de entrada
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'dosage' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:active,suspended,completed',
        ]);

        // Verificar duplicados excluyendo el registro actual
        $exists = PatientMedicine::where('user_id', $user->id)
            ->where('medicine_id', $request->medicine_id)
            ->where('id', '!=', $patientMedicine->id)
            ->exists();

        if ($exists) {
            return redirect()->route('users.medicines', $user)
                ->with('error', 'Este medicamento ya está asignado al usuario.');
        }

        // Actualizar medicamento
        $patientMedicine->update($request->all());

        return redirect()->route('users.medicines', $user)
            ->with('success', 'Medicamento actualizado exitosamente.');
    }

    /**
     * Eliminar medicamento asignado a un paciente
     * 
     * @param \App\Models\User $user
     * @param \App\Models\PatientMedicine $patientMedicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeMedicine(User $user, PatientMedicine $patientMedicine)
    {
        $patientMedicine->delete();
        
        return redirect()->route('users.medicines', $user)
            ->with('success', 'Medicamento removido exitosamente.');
    }
}