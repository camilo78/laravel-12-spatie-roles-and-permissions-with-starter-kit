<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de medicamentos
 * Maneja las operaciones CRUD para el modelo Medicine
 */
class MedicineController extends Controller
{


    /**
     * Mostrar formulario para crear nuevo medicamento
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('medicines.create');
    }

    /**
     * Almacenar nuevo medicamento en la base de datos
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'required|string|max:255',
            'presentation' => 'required|string|max:255',
            'concentration' => 'required|string|max:255',
        ]);

        // Crear nuevo medicamento
        Medicine::create($request->all());

        return redirect()->route('medicines.index')
            ->with('success', 'Medicamento creado exitosamente.');
    }

    /**
     * Mostrar detalles de un medicamento específico
     * 
     * @param \App\Models\Medicine $medicine
     * @return \Illuminate\View\View
     */
    public function show(Medicine $medicine)
    {
        return view('medicines.show', compact('medicine'));
    }

    /**
     * Mostrar formulario para editar medicamento
     * 
     * @param \App\Models\Medicine $medicine
     * @return \Illuminate\View\View
     */
    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    /**
     * Actualizar medicamento en la base de datos
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Medicine $medicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Medicine $medicine)
    {
        // Validar datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'required|string|max:255',
            'presentation' => 'required|string|max:255',
            'concentration' => 'required|string|max:255',
        ]);

        // Actualizar medicamento
        $medicine->update($request->all());

        return redirect()->route('medicines.index')
            ->with('success', 'Medicamento actualizado exitosamente.');
    }

    /**
     * Eliminar medicamento de la base de datos
     * 
     * @param \App\Models\Medicine $medicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        
        return redirect()->route('medicines.index')
            ->with('success', 'Medicamento eliminado exitosamente.');
    }
}