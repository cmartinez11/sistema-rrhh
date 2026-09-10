<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleta de Pago - Plásticos Fénix</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased; color: #334155;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 15px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01); border: 1px solid #e2e8f0;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #15803d; padding: 32px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase;">
                                PLÁSTICOS FÉNIX
                            </h1>
                            <p style="color: #dcfce7; margin: 6px 0 0 0; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                                Departamento de Recursos Humanos — Notificación Oficial
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #0f172a; font-size: 18px; font-weight: 700; margin: 0 0 16px 0;">
                                Estimado(a) {{ $boleta->empleado->nombres }} {{ $boleta->empleado->apellidos }},
                            </h2>
                            
                            @if (!empty($cuerpoCustom))
                                <div style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 24px;">
                                    {!! $cuerpoCustom !!}
                                </div>
                            @else
                                <p style="font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 24px 0;">
                                    Le remitimos por este medio oficial su <strong>Boleta de Pago de Haberes</strong> correspondiente al periodo <strong>{{ $boleta->periodo_formateado }}</strong>.
                                </p>
                            @endif

                            <!-- Information Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px; color: #334155;">
                                            <tr>
                                                <td style="padding: 6px 0; font-weight: 700; color: #64748b; width: 35%;">DNI / N° Doc:</td>
                                                <td style="padding: 6px 0; font-weight: 600; color: #0f172a; font-family: monospace;">{{ $boleta->empleado->dni }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; font-weight: 700; color: #64748b;">Periodo:</td>
                                                <td style="padding: 6px 0; font-weight: 600; color: #15803d;">{{ $boleta->periodo_formateado }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; font-weight: 700; color: #64748b;">Área:</td>
                                                <td style="padding: 6px 0; font-weight: 600; color: #0f172a;">{{ $boleta->empleado->area->nombre ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; font-weight: 700; color: #64748b;">Cargo:</td>
                                                <td style="padding: 6px 0; font-weight: 600; color: #0f172a;">{{ $boleta->empleado->cargo->nombre ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Attachment Notice -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0fdf4; border-radius: 12px; border: 1px solid #bbf7d0; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 16px 20px; font-size: 13px; color: #166534;">
                                        <strong>📎 Archivo Adjunto:</strong> Su boleta de pago se encuentra adjunta en formato PDF a este correo electrónico (<code>Boleta_{{ $boleta->empleado->dni }}.pdf</code>).
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; line-height: 1.5; color: #64748b; margin: 0;">
                                Si tiene dudas o alguna observación referente al contenido de su boleta de pago, por favor póngase en contacto directamente con el área de Recursos Humanos.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="font-size: 12px; font-weight: 700; color: #334155; margin: 0 0 4px 0;">
                                PLÁSTICOS FÉNIX
                            </p>
                            <p style="font-size: 11px; color: #94a3b8; margin: 0 0 16px 0;">
                                Este es un mensaje automatizado generado por el sistema de RRHH. Por favor no responda directamente a esta dirección de correo electrónico.
                            </p>
                            <p style="font-size: 10px; line-height: 1.4; color: #cbd5e1; margin: 0; text-align: justify;">
                                <strong>AVISO DE CONFIDENCIALIDAD:</strong> La información contenida en este correo electrónico y en sus archivos adjuntos es privada, confidencial y está protegida por la legislación vigente de protección de datos personales. Está destinada únicamente para el uso del destinatario final. Si usted no es el destinatario intencional, queda estrictamente prohibida cualquier revisión, copia, divulgación o distribución de su contenido.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
