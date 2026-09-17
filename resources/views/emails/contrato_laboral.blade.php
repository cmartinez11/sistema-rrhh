<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Contrato Laboral</title>
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
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-vigente { background-color: #dcfce7; color: #166534; }
        .badge-por_vencer { background-color: #fef3c7; color: #92400e; }
        .badge-vencido { background-color: #fee2e2; color: #991b1b; }
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
            <img src="{{ $message->embed(public_path('logo2.png')) }}" alt="PLASTICOS FENIX" style="max-height: 45px; width: auto; display: block; margin: 0 auto; border: 0;" />
            <p>Departamento de Recursos Humanos - Notificación Oficial</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Estimado(a) {{ $contrato->empleado->nombres }} {{ $contrato->empleado->apellidos }},
            </div>

            <p>Le saludamos cordialmente. A través del presente correo oficial le hacemos entrega del documento digital correspondiente a su <strong>Contrato Laboral</strong> con Plásticos Fénix.</p>

            <!-- Info Box -->
            <div class="info-box">
                <table class="info-table">
                    <tr>
                        <td class="label">DNI:</td>
                        <td class="value">{{ $contrato->empleado->dni }}</td>
                    </tr>
                    <tr>
                        <td class="label">Área:</td>
                        <td class="value">{{ $contrato->empleado->area->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Cargo:</td>
                        <td class="value">{{ $contrato->empleado->cargo->nombre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Fecha de Ingreso a la Empresa:</td>
                        <td class="value">{{ \Carbon\Carbon::parse($contrato->fecha_ingreso)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Vigencia del Contrato:</td>
                        <td class="value">
                            Del <strong>{{ \Carbon\Carbon::parse($contrato->fecha_inicio)->format('d/m/Y') }}</strong> 
                            al <strong>{{ \Carbon\Carbon::parse($contrato->fecha_fin)->format('d/m/Y') }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Estado de Vigencia:</td>
                        <td class="value">
                            <span class="badge badge-{{ $contrato->alerta_vencimiento }}">
                                {{ $contrato->alerta_label }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <p>En el archivo adjunto encontrará la copia en formato PDF de su contrato de trabajo. Le recomendamos descargar y conservar dicho documento para sus archivos personales.</p>

            <p>Si tiene alguna duda o consulta respecto a su contrato, por favor comuníquese directamente con la oficina de Recursos Humanos.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>PLÁSTICOS FÉNIX</strong></p>
            <p>Este es un correo automático generado por el Sistema de Recursos Humanos.</p>
            <p>Por favor no responda a esta dirección de correo electrónico.</p>
        </div>
    </div>
</body>
</html>
