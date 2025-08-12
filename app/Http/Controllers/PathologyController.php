<?php

namespace App\Http\Controllers;

use App\Models\Pathology;
use Illuminate\Http\Request;

class PathologyController extends Controller
{
    public function create()
    {
        return view('pathologies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|max:10|unique:pathologies',
            'descripcion' => 'required|string|max:500'
        ]);

        Pathology::create($request->all());

        return redirect()->route('pathologies.index')->with('success', 'Patología creada correctamente');
    }

    public function show(Pathology $pathology)
    {
        return view('pathologies.show', compact('pathology'));
    }

    public function edit(Pathology $pathology)
    {
        return view('pathologies.edit', compact('pathology'));
    }

    public function update(Request $request, Pathology $pathology)
    {
        $request->validate([
            'clave' => 'required|string|max:10|unique:pathologies,clave,' . $pathology->id,
            'descripcion' => 'required|string|max:500'
        ]);

        $pathology->update($request->all());

        return redirect()->route('pathologies.index')->with('success', 'Patología actualizada correctamente');
    }

    public function destroy(Pathology $pathology)
    {
        $pathology->delete();
        return redirect()->route('pathologies.index')->with('success', 'Patología eliminada correctamente');
    }
}