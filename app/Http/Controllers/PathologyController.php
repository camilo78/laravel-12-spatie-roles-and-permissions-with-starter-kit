<?php

namespace App\Http\Controllers;

use App\Models\Pathology;
use Illuminate\Http\Request;

class PathologyController extends Controller
{
    public function index()
    {
        $pathologies = Pathology::paginate(10);
        return view('pathologies.index', compact('pathologies'));
    }

    public function create()
    {
        return view('pathologies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:pathologies',
            'description' => 'nullable|string',
        ]);

        Pathology::create($request->all());

        return redirect()->route('pathologies.index')->with('success', 'Patología creada exitosamente.');
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:pathologies,code,' . $pathology->id,
            'description' => 'nullable|string',
        ]);

        $pathology->update($request->all());

        return redirect()->route('pathologies.index')->with('success', 'Patología actualizada exitosamente.');
    }

    public function destroy(Pathology $pathology)
    {
        $pathology->delete();
        return redirect()->route('pathologies.index')->with('success', 'Patología eliminada exitosamente.');
    }
}