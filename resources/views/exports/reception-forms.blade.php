<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formatos de Recepción - Medicamento en Casa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 1cm;
            color: #000;
            line-height: 1.4;
        }
        header {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }
        .titulo {
            font-size: 11pt;
            font-weight: bold;
            margin: 5px 0;
        }
        .seccion {
            margin-top: 14px;
            margin-bottom: 14px;
        }
        .campo {
            margin-top: 16px;
        }
        .firma {
            margin-top: 20px;
        }
        .nota {
            margin-top: 5px;
            font-size: 9pt;
            color: #555;
        }

        p {
            margin: 8px 0;
            line-height: 1.3;
            text-align: justify;
        }
        .page-break {
            page-break-after: always;
        }
        @page {
            margin: 1cm;
        }
        .page-number {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 10pt;
            color: #666;
        }
    </style>
</head>
<body>
    @foreach($patients as $index => $patient)

        
        <div class="page-break">
            <header>
                <img src="{{ $appLogo ? storage_path('app/public/' . $appLogo) : public_path('img/salud.png') }}" alt="Logo Salud" style="position: absolute; left: 0; top: 0; height: 60px;">
                <img src="{{ $hospitalLogo ? storage_path('app/public/' . $hospitalLogo) : public_path('img/hga.png') }}" alt="Logo Hospital" style="position: absolute; right: 0; top: 0; height: 65px;">
                
                <div><strong>{{ $programName ?? 'Programa de Entrega de Medicamentos en Casa' }}</strong></div>
                <div class="titulo">FORMATO DE RECEPCIÓN PROYECTO MEDICAMENTO EN CASA</div>
                <div>{{ $hospitalName ?? 'Hospital General Atlántida' }}</div>
            </header>

            <div class="seccion" style="margin-top: 25px">
                <div class="campo">Fecha de entrega: _________________________________________ Hora: ___________________</div>
            </div>

            <div class="seccion">
                <strong>Datos del Paciente:</strong>
                <div class="campo">Nombre Completo: {{ $patient->name }}</div>
                <div>Número de Identidad: {{ $patient->dni }}</div>
                <div>Dirección: {{ $patient->address }}</div>
                <div>Teléfono: {{ $patient->phone ?? 'No especificado' }}</div>
            </div>



            <div class="seccion">
                <strong >Declaración del Paciente:</strong>
                <p>
                    Declaro haber recibido a conformidad los medicamentos descritos en el listado anterior, verificando que coinciden con mi tratamiento prescrito, confirmo haber verificado la integridad de la bolsa la cual viene sellada. Me comprometo a hacer uso responsable de los mismos según las indicaciones médicas.
                </p>
            </div>

            <div class="seccion firma">
                <strong >Paciente o Responsable de recibir el medicamento:</strong>
                <div class="campo">Nombre: _________________________________________________________________________</div>
                <div class="campo">Identidad: ________________________________________________________________________</div>
                <div class="campo">Firma: ________________________________ Fecha: ____________________________________</div>
            </div>

            <div class="seccion firma">
                <strong >Responsable de Entrega en el Hospital:</strong>
                <div class="campo">Nombre: _________________________________________________________________________</div>
                <div class="campo">Identidad: ________________________________________________________________________</div>
                <div class="campo">Firma: ________________________________ Fecha: ____________________________________</div>
            </div>

            <div class="seccion firma">
                <strong >Personal Responsable de Entregar el Medicamento (Empresa):</strong>
                <div class="campo">Nombre: _________________________________________________________________________</div>
                <div class="campo">Identidad: ________________________________________________________________________</div>
                <div class="campo">Firma: ________________________________ Fecha: ____________________________________</div>
            </div>

            <div class="nota">
                Nota: Se adjunta formato de SALMI, donde se nombran los medicamentos que se entregan al paciente.
            </div>
        </div>
    @endforeach
</body>
</html>