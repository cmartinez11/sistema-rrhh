<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Documento Laboral</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #15803d;
            padding: 24px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #bbf7d0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 30px;
            color: #334155;
            line-height: 1.6;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .info-box {
            background-color: #f8fafc;
            border-left: 4px solid #15803d;
            padding: 16px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .info-table td {
            padding: 6px 0;
        }
        .info-table td.label {
            font-weight: 600;
            color: #475569;
            width: 45%;
        }
        .info-table td.value {
            color: #0f172a;
        }
        .footer {
            background-color: #0f172a;
            padding: 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.5;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>PLÁSTICOS FÉNIX</h1>
            <p>Departamento de Recursos Humanos - Notificación Oficial</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Estimado(a) {{ $documento->empleado->nombres }} {{ $documento->empleado->apellidos }},
            </div>

            <p>Le saludamos cordialmente. A través del presente correo oficial le hacemos entrega del documento digital correspondiente a su <strong>{{ $documento->tipo_nombre }}</strong> emitido por PLASTICOS FENIX.</p>

            <!-- Info Box -->
            <div class="info-box">
                <table class="info-table">
                    <tr>
                        <td class="label">Tipo de Documento:</td>
                        <td class="value"><strong>{{ $documento->tipo_nombre }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">DNI Empleado:</td>
                        <td class="value">{{ $documento->empleado->dni }}</td>
                    </tr>
                    <tr>
                        <td class="label">Área:</td>
                        <td class="value">{{ $documento->empleado->area->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Cargo:</td>
                        <td class="value">{{ $documento->empleado->cargo->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Fecha de Emisión:</td>
                        <td class="value">{{ \Carbon\Carbon::parse($documento->fecha_emision)->format('d/m/Y') }}</td>
                    </tr>
                    @if($documento->asunto_motivo)
                    <tr>
                        <td class="label">Asunto / Motivo:</td>
                        <td class="value">{{ $documento->asunto_motivo }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p>En el archivo adjunto encontrará la copia en formato PDF de dicho documento laboral. Le recomendamos descargar y conservar dicho archivo para sus registros personales.</p>

            <p>Si tiene alguna duda o consulta al respecto, por favor comuníquese directamente con la oficina de Recursos Humanos.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>PLASTICOS FENIX</strong></p>
            <p>Este es un correo automático generado por el Sistema de Recursos Humanos.</p>
            <p>Por favor no responda a esta dirección de correo electrónico.</p>
        </div>
    </div>
</body>
</html>
