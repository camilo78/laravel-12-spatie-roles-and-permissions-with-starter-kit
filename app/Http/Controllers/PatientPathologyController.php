<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pathology;
use App\Models\PatientPathology;
use Illuminate\Http\Request;

class PatientPathologyController extends Controller
{
    public function userPathologies(User $user)
    {
        $patientPathologies = $user->patientPathologies()->with('pathology')->get();
        $pathologies = Pathology::orderBy('clave')->get();
        return view('users.pathologies', compact('user', 'patientPathologies', 'pathologies'));
    }

    public function assignPathology(Request $request, User $user)
    {
        $request->validate([
            'pathology_id' => 'required|exists:pathologies,id',
            'diagnosed_at' => 'required|date',
            'status' => 'required|in:active,inactive,controlled'
        ]);

        PatientPathology::create([
            'user_id' => $user->id,
            'pathology_id' => $request->pathology_id,
            'diagnosed_at' => $request->diagnosed_at,
            'status' => $request->status
        ]);

        return redirect()->route('users.pathologies', $user)->with('success', 'Patología asignada correctamente');
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
            'status' => 'required|in:active,inactive,controlled'
        ]);

        $patientPathology->update($request->all());

        return redirect()->route('users.pathologies', $user)->with('success', 'Patología actualizada correctamente');
    }

    public function removePathology(User $user, PatientPathology $patientPathology)
    {
        $patientPathology->delete();
        return redirect()->route('users.pathologies', $user)->with('success', 'Patología eliminada correctamente');
    }
}