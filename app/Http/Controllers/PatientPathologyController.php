<?php

namespace App\Http\Controllers;

use App\Models\PatientPathology;
use App\Models\User;
use App\Models\Pathology;
use Illuminate\Http\Request;

class PatientPathologyController extends Controller
{
    public function userPathologies(User $user)
    {
        $pathologies = Pathology::all();
        $userPathologies = $user->patientPathologies()->with('pathology')->get();
        return view('users.pathologies', compact('user', 'pathologies', 'userPathologies'));
    }

    public function assignPathology(Request $request, User $user)
    {
        $request->validate([
            'pathology_id' => 'required|exists:pathologies,id',
            'diagnosed_at' => 'required|date',
            'status' => 'required|in:active,inactive,controlled',
        ]);

        // Verificar si ya existe la combinación
        $exists = PatientPathology::where('user_id', $user->id)
            ->where('pathology_id', $request->pathology_id)
            ->exists();

        if ($exists) {
            return redirect()->route('users.pathologies', $user)
                ->with('error', 'Esta patología ya está asignada al usuario.');
        }

        PatientPathology::create([
            'user_id' => $user->id,
            'pathology_id' => $request->pathology_id,
            'diagnosed_at' => $request->diagnosed_at,
            'status' => $request->status,
        ]);

        return redirect()->route('users.pathologies', $user)->with('success', 'Patología asignada exitosamente.');
    }

    public function editPathology(User $user, PatientPathology $patientPathology)
    {
        $pathologies = Pathology::all();
        return view('users.pathologies-edit', compact('user', 'patientPathology', 'pathologies'));
    }

    public function updatePathology(Request $request, User $user, PatientPathology $patientPathology)
    {
        $request->validate([
            'pathology_id' => 'required|exists:pathologies,id',
            'diagnosed_at' => 'required|date',
            'status' => 'required|in:active,inactive,controlled',
        ]);

        // Verificar si ya existe la combinación (excluyendo el registro actual)
        $exists = PatientPathology::where('user_id', $user->id)
            ->where('pathology_id', $request->pathology_id)
            ->where('id', '!=', $patientPathology->id)
            ->exists();

        if ($exists) {
            return redirect()->route('users.pathologies', $user)
                ->with('error', 'Esta patología ya está asignada al usuario.');
        }

        $patientPathology->update($request->all());

        return redirect()->route('users.pathologies', $user)->with('success', 'Patología actualizada exitosamente.');
    }

    public function removePathology(User $user, PatientPathology $patientPathology)
    {
        $patientPathology->delete();
        return redirect()->route('users.pathologies', $user)->with('success', 'Patología removida exitosamente.');
    }
}