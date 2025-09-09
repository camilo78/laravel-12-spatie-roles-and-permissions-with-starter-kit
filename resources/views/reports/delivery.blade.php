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
            content: "Página " counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $appLogo ? storage_path('app/public/' . $appLogo) : public_path('img/salud.png') }}" alt="Logo Salud" style="float: left; height: 125px; margin-top:20px">
        <img src="{{ $hospitalLogo ? storage_path('app/public/' . $hospitalLogo) : public_path('img/hga.png') }}" alt="Logo Hospital" style="float: right; height: 138px; margin-top:13px">
        <div>
            <h1>Reporte de Entrega de Medicamentos</h1>
            <h2>{{ $delivery->name }}</h2>
            <p>Fecha: {{ $delivery->start_date ? \Carbon\Carbon::parse($delivery->start_date)->format('d/m/Y') : 'N/A' }} - {{ $delivery->end_date ? \Carbon\Carbon::parse($delivery->end_date)->format('d/m/Y') : 'N/A' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <div class="page-number"></div>

    <div class="section">
        <h3>Resumen Estadístico</h3>
        <table>
            <tr>
                <td><strong>Pacientes Entregados:</strong></td>
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
                    <td>{{ $municipality }}</td>
                    <td>{{ $count }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="2">Total</th>
                    <th>{{ $municipalities->sum() }}</th>
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
                    <td>{{ $pathology['code'] }}</td>
                    <td>{{ $pathology['description'] }}</td>
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
        <h3>Detalle de Entregas por Paciente</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>DNI</th>
                    <th>Estado Entrega</th>
                    <th>Medicamentos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delivery->deliveryPatients->where('state', 'entregada') as $index => $patient)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $patient->user->name }}</td>
                        <td>{{ $patient->user->dni ?: 'N/A' }}</td>
                        <td>
                            @php
                                $stateColors = [
                                    'programada' => '#666',
                                    'en_proceso' => '#f59e0b',
                                    'entregada' => '#10b981',
                                    'no_entregada' => '#ef4444'
                                ];
                                $stateLabels = [
                                    'programada' => 'Programada',
                                    'en_proceso' => 'En Proceso',
                                    'entregada' => 'Entregada',
                                    'no_entregada' => 'No Entregada'
                                ];
                            @endphp
                            <span style="color: {{ $stateColors[$patient->state] }}; font-weight: bold;">
                                {{ $stateLabels[$patient->state] }}
                            </span>
                        </td>
                        <td>
                            @foreach($patient->deliveryMedicines as $deliveryMedicine)
                                <div style="margin-bottom: 3px; padding: 2px; {{ $deliveryMedicine->included ? 'background-color: #f0f9ff;' : 'background-color: #fef2f2;' }}">
                                    <strong>{{ $deliveryMedicine->patientMedicine->medicine->generic_name }}</strong>
                                    ({{ $deliveryMedicine->patientMedicine->medicine->presentation ?? 'N/A' }})
                                    - Cantidad: {{ $deliveryMedicine->patientMedicine->quantity }}
                                    @if($deliveryMedicine->included)
                                        <span style="color: #10b981; font-size: 10px;"> Entregado</span>
                                    @else
                                        <span style="color: #ef4444; font-size: 10px;"> No Entregado</span>
                                        @if($deliveryMedicine->observations)
                                            <br><em style="font-size: 10px; color: #666;">{{ $deliveryMedicine->observations }}</em>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td></td>
                    <td colspan="2">Total Pacientes</td>
                    <td>{{ $delivery->deliveryPatients->count() }}</td>
                    <td>
                        Total Entregados: {{ $delivery->deliveryPatients->where('state', 'entregada')->count() }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <h3 style="background-color: #ffebee;">Pacientes NO Entregados</h3>
        @if(isset($notDeliveredPatients) && $notDeliveredPatients->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paciente</th>
                    <th>DNI</th>
                    <th>Medicamentos Programados</th>
                    <th>Motivo de No Entrega</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notDeliveredPatients as $index => $patient)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $patient->user->name }}</td>
                    <td>{{ $patient->user->dni ?: 'N/A' }}</td>
                    <td>
                        @foreach($patient->deliveryMedicines as $deliveryMedicine)
                            <div style="margin-bottom: 3px;">
                                {{ $deliveryMedicine->patientMedicine->medicine->generic_name }}
                                ({{ $deliveryMedicine->patientMedicine->medicine->presentation ?? 'N/A' }})
                                - Cantidad: {{ $deliveryMedicine->patientMedicine->quantity }}
                            </div>
                        @endforeach
                    </td>
                    <td>{{ $patient->delivery_notes ?? 'Sin motivo especificado' }}</td>
                </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #ffebee;">
                    <td></td>
                    <td colspan="2">Total Pacientes NO Entregados</td>
                    <td colspan="2">{{ $notDeliveredPatients->count() }}</td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #666; font-style: italic;">Todos los pacientes fueron entregados exitosamente.</p>
        @endif
    </div>
    
    <div class="section" style="margin-top: 40px; text-align: center; page-break-inside: avoid;">
        <div style="margin-bottom: 60px;"></div>
        <div style="border-top: 1px solid #000; width: 300px; margin: 0 auto;"></div>
        <p style="margin: 0; padding: 0; font-weight: bold; font-size: 14px;">{{ $programManager }}</p>
        <p style="margin: 0; padding: 0; font-size: 11;">{{ $programName }}</p>
        <p style="margin: 0; padding: 0; font-weight: bold; font-size: 14px;">{{ $hospitalName }}</p>
        
        <div style="margin-top: 10px; font-size: 12px; color: #666;">
            <p style="margin: 0; padding: 0;">© {{ $programName }}
            <p style="margin: 0; padding: 0;">{{ $hospitalName }}, Secretaría de Salud</p>
        </div>
    </div>
    
    <div class="page-number"></div>
</body>
</html>