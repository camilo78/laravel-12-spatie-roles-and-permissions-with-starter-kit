<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Entregas para {{ $weekStart }} - {{ $weekEnd }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 3px; text-align: left; }
        th { background-color: #f9f9f9; font-weight: bold; font-size: 9px; }
        .insulin-patient { background-color: #ffebee !important; }
        .medicine-list { font-size: 8px; }
        .medicine-item { margin-bottom: 1px; }
        .insulin-medicine { color: #d32f2f; font-weight: bold; }
        @page { margin: 1cm; size: A4 landscape; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $appLogo ? storage_path('app/public/' . $appLogo) : public_path('img/salud.png') }}" alt="Logo Salud" style="float: left; height: 80px; margin-top:10px">
        <img src="{{ $hospitalLogo ? storage_path('app/public/' . $hospitalLogo) : public_path('img/hga.png') }}" alt="Logo Hospital" style="float: right; height: 85px; margin-top:7px">
        <div>
            <h1 style="margin: 0; font-size: 18px;">Entregas para {{ $weekStart }} - {{ $weekEnd }}</h1>
            <h2 style="margin: 5px 0; font-size: 14px; color: #8bcede; text-shadow: 2px 2px 4px rgba(0,0,0,0.6);">{{ $programName ?? 'Programa de Entrega de Medicamentos en Casa' }}</h2>
            <p style="margin: 5px 0; font-size: 12px;">{{ $hospitalName ?? 'Hospital General Atlántida' }}</p>
            <p style="margin: 5px 0; font-size: 10px;">Total de pacientes: {{ count($patients) }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Departamento</th>
                <th>Municipio</th>
                <th>Localidad</th>
                <th>Dirección</th>
                <th>Género</th>
                <th>Fecha Ingreso</th>
                <th>Medicamentos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $index => $patient)
                @php
                    $hasInsulin = false;
                    $medicines = [];
                    
                    if ($patient->deliveryPatients->first()) {
                        $deliveryPatient = $patient->deliveryPatients->first();
                        foreach ($deliveryPatient->deliveryMedicines as $deliveryMedicine) {
                            $medicineName = $deliveryMedicine->patientMedicine->medicine->generic_name;
                            $medicines[] = $medicineName . ' (' . ($deliveryMedicine->patientMedicine->medicine->presentation ?? 'N/A') . ')';
                            
                            if (stripos($medicineName, 'insulina') !== false) {
                                $hasInsulin = true;
                            }
                        }
                    }
                @endphp
                
                <tr class="{{ $hasInsulin ? 'insulin-patient' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $patient->name }}</td>
                    <td>{{ $patient->dni }}</td>
                    <td>{{ $patient->phone ?? 'N/A' }}</td>
                    <td>{{ $patient->department->name ?? 'N/A' }}</td>
                    <td>{{ $patient->municipality->name ?? 'N/A' }}</td>
                    <td>{{ $patient->locality->name ?? 'N/A' }}</td>
                    <td>{{ $patient->address }}</td>
                    <td>{{ $patient->gender ?? 'N/A' }}</td>
                    <td>{{ $patient->admission_date ? $patient->admission_date->format('d/m/Y') : 'N/A' }}</td>
                    <td class="medicine-list">
                        @foreach($medicines as $medicine)
                            <div class="medicine-item {{ stripos($medicine, 'insulina') !== false ? 'insulin-medicine' : '' }}">
                                {{ $medicine }}
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 9px; color: #666;">
        <p><strong>Nota:</strong> Los pacientes sombreados en rojo requieren insulina.</p>
        <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>