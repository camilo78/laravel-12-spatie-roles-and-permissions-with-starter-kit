<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Exports\UsersTemplateExport;

class UserExcelController extends Controller
{
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls,csv']);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->back()->with('success', 'Usuarios importados exitosamente.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Fila {$failure->row()}: {$failure->errors()[0]}";
            }
            return redirect()->back()->with('error', 'Errores de validación:' . implode(', ', array_slice($errors, 0, 5)));
        } catch (\Exception $e) {
            Log::error('Error durante la importación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al importar el archivo.');
        }
    }

    public function template()
    {
        // Descarga el archivo usando la clase de exportación de plantilla
        return Excel::download(new UsersTemplateExport, 'plantilla_usuarios_importacion.xlsx');
    }
}