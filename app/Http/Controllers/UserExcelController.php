<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class UserExcelController extends Controller
{
    public function export()
    {
        $users = User::all();
        $filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            echo $this->generateXlsx($users, false);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function template()
    {
        $filename = 'plantilla_usuarios.xlsx';
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            echo $this->generateXlsx([], true);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateXlsx($users, $isTemplate = false)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE);

        // [Content_Types].xml
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>');

        // _rels/.rels
        $zip->addEmptyDir('_rels');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');

        // xl/_rels/workbook.xml.rels
        $zip->addEmptyDir('xl');
        $zip->addEmptyDir('xl/_rels');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>');

        // xl/workbook.xml
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Users" sheetId="1" r:id="rId1"/></sheets></workbook>');

        // xl/worksheets/sheet1.xml
        $zip->addEmptyDir('xl/worksheets');
        $sheetData = $this->generateSheetData($users, $isTemplate);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetData);

        $zip->close();
        
        $content = file_get_contents($tempFile);
        unlink($tempFile);
        
        return $content;
    }

    private function generateSheetData($users, $isTemplate)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        
        // Headers
        $xml .= '<row r="1">';
        $headers = ['name', 'email', 'dui', 'phone', 'address', 'department_id', 'municipality_id', 'locality_id', 'gender', 'status', 'password'];
        foreach ($headers as $index => $header) {
            $cellRef = chr(65 + $index) . '1';
            $xml .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . htmlspecialchars($header) . '</t></is></c>';
        }
        $xml .= '</row>';

        if ($isTemplate) {
            // Template data
            $xml .= '<row r="2">';
            $templateData = ['Juan Pérez', 'juan@example.com', '12345678-9', '7890-1234', 'Calle Principal #123', '1', '1', '1', 'masculino', '1', 'password123'];
            foreach ($templateData as $index => $value) {
                $cellRef = chr(65 + $index) . '2';
                $xml .= '<c r="' . $cellRef . '" t="inlineStr"><is><t>' . htmlspecialchars($value) . '</t></is></c>';
            }
            $xml .= '</row>';
        } else {
            // User data
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
                    ''
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

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt,xlsx,xls']);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'xlsx' || $extension === 'xls') {
            $data = $this->readXlsx($file->getRealPath());
        } else {
            $data = array_map('str_getcsv', file($file->getRealPath()));
        }
        
        if (empty($data)) {
            return redirect()->back()->with('error', 'El archivo está vacío.');
        }

        array_shift($data);
        $errors = [];
        $imported = 0;

        foreach ($data as $index => $row) {
            $rowNumber = $index + 2;
            
            if (count($row) < 11) {
                $errors[] = "Fila {$rowNumber}: Faltan columnas";
                continue;
            }

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

            $validator = Validator::make($userData, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6'
            ]);

            if ($validator->fails()) {
                $errors[] = "Fila {$rowNumber}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            try {
                $userData['password'] = Hash::make($userData['password']);
                User::create($userData);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Fila {$rowNumber}: " . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', "Importados: {$imported}. Errores:\n" . implode("\n", array_slice($errors, 0, 5)));
        }

        return redirect()->back()->with('success', "Se importaron {$imported} usuarios exitosamente.");
    }

    private function readXlsx($filePath)
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== TRUE) {
            return [];
        }

        $sheetData = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetData) {
            return [];
        }

        $xml = simplexml_load_string($sheetData);
        $data = [];

        foreach ($xml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $value = '';
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