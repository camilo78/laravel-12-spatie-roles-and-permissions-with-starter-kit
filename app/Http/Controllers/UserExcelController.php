<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

/**
 * Controlador para la exportación e importación de usuarios en formato Excel
 * Maneja la generación de archivos XLSX sin dependencias externas
 */
class UserExcelController extends Controller
{
    /**
     * Exportar todos los usuarios a un archivo Excel
     * 
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        // Obtener todos los usuarios
        $users = User::all();
        
        // Generar nombre de archivo con timestamp
        $filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Configurar headers para descarga de Excel
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Callback para generar el contenido del archivo
        $callback = function() use ($users) {
            echo $this->generateXlsx($users, false);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generar plantilla de Excel para importación de usuarios
     * 
     * @return \Illuminate\Http\Response
     */
    public function template()
    {
        $filename = 'plantilla_usuarios.xlsx';
        
        // Configurar headers para descarga de plantilla
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Callback para generar plantilla vacía con ejemplo
        $callback = function() {
            echo $this->generateXlsx([], true);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generar archivo XLSX manualmente usando ZipArchive
     * 
     * @param array $users Lista de usuarios
     * @param bool $isTemplate Si es plantilla o exportación real
     * @return string Contenido del archivo XLSX
     */
    private function generateXlsx($users, $isTemplate = false)
    {
        // Crear archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE);

        // Agregar archivo de tipos de contenido
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');

        // Agregar relaciones principales
        $zip->addEmptyDir('_rels');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');

        // Agregar relaciones del workbook
        $zip->addEmptyDir('xl');
        $zip->addEmptyDir('xl/_rels');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');

        // Agregar definición del workbook
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Users" sheetId="1" r:id="rId1"/></sheets></workbook>');

        // Generar y agregar datos de la hoja
        $zip->addEmptyDir('xl/worksheets');
        $sheetData = $this->generateSheetData($users, $isTemplate);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetData);

        $zip->close();
        
        // Leer contenido y limpiar archivo temporal
        $content = file_get_contents($tempFile);
        unlink($tempFile);
        
        return $content;
    }

    /**
     * Generar datos XML de la hoja de cálculo
     * 
     * @param array $users Lista de usuarios
     * @param bool $isTemplate Si es plantilla con datos de ejemplo
     * @return string XML de la hoja
     */
    private function generateSheetData($users, $isTemplate)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        
        // Generar fila de encabezados
        $xml .= '<row r="1">';
        $headers = ['name', 'email', 'dui', 'phone', 'address', 'department_id', 'municipality_id', 'locality_id', 'gender', 'status', 'password'];
        foreach ($headers as $index => $header) {
            $cellRef = chr(65 + $index) . '1';
            $xml .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . htmlspecialchars($header) . '</t></is></c>';
        }
        $xml .= '</row>';

        if ($isTemplate) {
            // Generar fila de ejemplo para plantilla
            $xml .= '<row r="2">';
            $templateData = ['Juan Pérez', 'juan@example.com', '12345678-9', '7890-1234', 'Calle Principal #123', '1', '1', '1', 'masculino', '1', 'password123'];
            foreach ($templateData as $index => $value) {
                $cellRef = chr(65 + $index) . '2';
                $xml .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . htmlspecialchars($value) . '</t></is></c>';
            }
            $xml .= '</row>';
        } else {
            // Generar filas con datos reales de usuarios
            $rowNum = 2;
            foreach ($users as $user) {
                $xml .= '<row r="' . $rowNum . '">';
                $userData = [
                    $user->name,
                    $user->email,
                    $user->dui,
                    $user->phone,
                    $user->address,
                    $user->department_id,
                    $user->municipality_id,
                    $user->locality_id,
                    $user->gender,
                    $user->status ? '1' : '0',
                    '' // Password vacío por seguridad
                ];
                foreach ($userData as $index => $value) {
                    $cellRef = chr(65 + $index) . $rowNum;
                    $xml .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . htmlspecialchars($value ?? '') . '</t></is></c>';
                }
                $xml .= '</row>';
                $rowNum++;
            }
        }
        
        $xml .= '</sheetData></worksheet>';
        return $xml;
    }

    /**
     * Importar usuarios desde archivo Excel o CSV
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        // Validar archivo de entrada
        $request->validate(['file' => 'required|file|mimes:csv,txt,xlsx,xls']);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        // Leer datos según el tipo de archivo
        if ($extension === 'xlsx' || $extension === 'xls') {
            $data = $this->readXlsx($file->getRealPath());
        } else {
            $data = array_map('str_getcsv', file($file->getRealPath()));
        }
        
        // Verificar que el archivo no esté vacío
        if (empty($data)) {
            return redirect()->back()->with('error', 'El archivo está vacío.');
        }

        // Remover fila de encabezados
        array_shift($data);
        $errors = [];
        $imported = 0;

        // Procesar cada fila de datos
        foreach ($data as $index => $row) {
            $rowNumber = $index + 2; // +2 porque removimos headers y empezamos en 1
            
            // Verificar que la fila tenga todas las columnas necesarias
            if (count($row) < 11) {
                $errors[] = "Fila {$rowNumber}: Faltan columnas";
                continue;
            }

            // Mapear datos de la fila a estructura de usuario
            $userData = [
                'name' => $row[0],
                'email' => $row[1],
                'dui' => $row[2],
                'phone' => $row[3],
                'address' => $row[4],
                'department_id' => $row[5] ?: null,
                'municipality_id' => $row[6] ?: null,
                'locality_id' => $row[7] ?: null,
                'gender' => $row[8],
                'status' => $row[9] == '1',
                'password' => $row[10]
            ];

            // Validar datos del usuario
            $validator = Validator::make($userData, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                $errors[] = "Fila {$rowNumber}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Intentar crear el usuario
            try {
                $userData['password'] = Hash::make($userData['password']);
                User::create($userData);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Fila {$rowNumber}: " . $e->getMessage();
            }
        }

        // Mostrar resultado de la importación
        if (!empty($errors)) {
            return redirect()->back()->with('error', "Importados: {$imported}. Errores:\n" . implode("\n", array_slice($errors, 0, 5)));
        }

        return redirect()->back()->with('success', "Se importaron {$imported} usuarios exitosamente.");
    }

    /**
     * Leer archivo XLSX y extraer datos
     * 
     * @param string $filePath Ruta del archivo XLSX
     * @return array Datos extraídos del archivo
     */
    private function readXlsx($filePath)
    {
        $zip = new ZipArchive();
        
        // Intentar abrir el archivo ZIP
        if ($zip->open($filePath) !== TRUE) {
            return [];
        }

        // Extraer datos de la primera hoja
        $sheetData = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetData) {
            return [];
        }

        // Parsear XML de la hoja
        $xml = simplexml_load_string($sheetData);
        $data = [];

        // Extraer datos de cada fila
        foreach ($xml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $value = '';
                // Obtener valor de la celda (texto inline o valor directo)
                if (isset($cell->is->t)) {
                    $value = (string)$cell->is->t;
                } elseif (isset($cell->v)) {
                    $value = (string)$cell->v;
                }
                $rowData[] = $value;
            }
            $data[] = $rowData;
        }

        return $data;
    }
}