<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Entrega - {{ $delivery->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; counter-reset: page; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .section h3 { background-color: #f0f0f0; padding: 5px; margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f9f9f9; }
        .stats { display: flex; justify-content: space-between; }
        .stat-box { text-align: center; padding: 10px; border: 1px solid #ddd; }
        @page {
            margin: 1.5cm;
        }
        .page-number {
            position: fixed;
            bottom: -0.5cm;
            right: 0cm;
            font-size: 12px;
            color: #666;
        }
        .page-number:after {
            content: "Página " counter(page) " de {{ $estimatedPages + 1 }}";
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('img/salud.png') }}" alt="Logo Salud" style="float: left; height: 125px; margin-top:20px">
        <img src="{{ public_path('img/hga.png') }}" alt="Logo HGA" style="float: right; height: 138px; margin-top:13px">
        <div>
            <h1>Reporte de Entrega de Medicamentos</h1>
            <h2>{{ $delivery->name }}</h2>
            <p>Fecha: {{ $delivery->start_date ? \Carbon\Carbon::parse($delivery->start_date)->format('d/m/Y') : 'N/A' }} - {{ $delivery->end_date ? \Carbon\Carbon::parse($delivery->end_date)->format('d/m/Y') : 'N/A' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="section">
        <h3>Resumen Estadístico</h3>
        <table>
            <tr>
                <td><strong>Total de Pacientes:</strong></td>
                <td>{{ $totalPatients }}</td>
                <td><strong>Hombres:</strong></td>
                <td>{{ $malePatients }}</td>
                <td><strong>Mujeres:</strong></td>
                <td>{{ $femalePatients }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Distribución por Departamentos</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Departamento</th>
                    <th>Cantidad de Pacientes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department => $count)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $department ?: 'No especificado' }}</td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Distribución por Municipios</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Municipio</th>
                    <th>Cantidad de Pacientes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($municipalities as $municipality => $count)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $municipality ?: 'No especificado' }}</td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="2">Total</th>
                    <th>{{ $totalMunicipalities }}</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Listado de Pacientes</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Género</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Localidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivery->deliveryPatients as $patient)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $patient->user->name }}</td>
                    <td>{{ $patient->user->dni ?: 'N/A' }}</td>
                    <td>{{ $patient->user->gender ?: 'N/A' }}</td>
                    <td>{{ $patient->user->department->name ?? 'N/A' }}</td>
                    <td>{{ $patient->user->municipality->name ?? 'N/A' }}</td>
                    <td>{{ $patient->user->locality->name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Medicamentos Entregados</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicamento</th>
                    <th>Presentación</th>
                    <th style="text-align: right;">Cantidad Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $medicine['name'] }}</td>
                    <td>{{ $medicine['presentation'] }}</td>
                    <td style="text-align: right;">{{ $medicine['quantity'] }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td></td>
                    <td colspan="2">Total Medicamentos</td>
                    <td style="text-align: right;">{{ $medicines->sum('quantity') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Patologías de los Pacientes</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Código CIE-10</th>
                    <th>Descripción</th>
                    <th style="text-align: right;">Cantidad de Pacientes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pathologies as $pathology)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pathology['clave'] }}</td>
                    <td>{{ $pathology['descripcion'] }}</td>
                    <td style="text-align: right;">{{ $pathology['count'] }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td></td>
                    <td colspan="2">Total Casos de Patologías</td>
                    <td style="text-align: right;">{{ $pathologies->sum('count') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Detalle de Medicamentos por Paciente</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>DNI</th>
                    <th>Medicamento</th>
                    <th>Presentación</th>
                    <th style="text-align: right;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($delivery->deliveryPatients as $patient)
                    @foreach($patient->deliveryMedicines as $deliveryMedicine)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $patient->user->name }}</td>
                            <td>{{ $patient->user->dni ?: 'N/A' }}</td>
                            <td>{{ $deliveryMedicine->patientMedicine->medicine->generic_name }}</td>
                            <td>{{ $deliveryMedicine->patientMedicine->medicine->presentation ?? 'N/A' }}</td>
                            <td style="text-align: right;">{{ $deliveryMedicine->patientMedicine->quantity }}</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td></td>
                    <td colspan="3">Total Tipos de Medicamentos</td>
                    <td>{{ $delivery->deliveryPatients->flatMap->deliveryMedicines->groupBy('patientMedicine.medicine.generic_name')->count() }}</td>
                    <td style="text-align: right;">{{ $delivery->deliveryPatients->sum(function($patient) { return $patient->deliveryMedicines->sum('patientMedicine.quantity'); }) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="section">
        <h3 style="background-color: #ffebee;">Medicamentos NO Entregados</h3>
        @if(isset($notDeliveredMedicines) && $notDeliveredMedicines->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>DNI</th>
                    <th>Medicamento</th>
                    <th>Presentación</th>
                    <th style="text-align: right;">Cantidad</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notDeliveredMedicines as $notDelivered)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $notDelivered['patient_name'] ?? 'N/A' }}</td>
                    <td>{{ $notDelivered['patient_dni'] ?? 'N/A' }}</td>
                    <td>{{ $notDelivered['medicine_name'] ?? 'N/A' }}</td>
                    <td>{{ $notDelivered['presentation'] ?? 'N/A' }}</td>
                    <td style="text-align: right;">{{ $notDelivered['quantity'] ?? 0 }}</td>
                    <td>{{ $notDelivered['reason'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #ffebee;">
                    <td></td>
                    <td colspan="2">Total Tipos de Medicamentos NO Entregados</td>
                    <td style="text-align: right;">{{ isset($notDeliveredMedicines) ? $notDeliveredMedicines->groupBy('medicine_name')->count() : 0 }}</td>
                    <td></td>
                    <td style="text-align: right;">{{ isset($notDeliveredMedicines) ? $notDeliveredMedicines->sum('quantity') : 0 }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #666; font-style: italic;">Todos los medicamentos fueron entregados exitosamente.</p>
        @endif
    </div>
    <div class="section" style="margin-top: 40px; text-align: center; page-break-inside: avoid;">
        <div style="margin-bottom: 60px;"></div>
        <div style="border-top: 1px solid #000; width: 300px; margin: 0 auto;"></div>
        <p style="margin: 10px 0 0 0; padding: 0; font-weight: bold; font-size: 14px;">Lic. Sandra Patricia Nuñez Hernández</p>
        <p style="margin: 0; padding: 0; font-size: 11;">Encargada del Programa de Entrega de Medicamentos en Casa</p>
        <p style="margin: 0; padding: 0; font-weight: bold; font-size: 14px;">Hospital General Atlántida</p>
        
        <div style="margin-top: 10px; font-size: 12px; color: #666;">
            <p style="margin: 0; padding: 0;">© Sistema de Entrega de Medicamentos en Casa</p>
            <p style="margin: 0; padding: 0;">Hospital General Atlántida, Secretaría de Salud</p>
        </div>
    </div>
    
    <div class="page-number"></div>
</body>
</html>