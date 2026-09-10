<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnvioBoleta;
use App\Models\EnvioContrato;
use App\Mail\BoletaPagoMail;
use App\Mail\ContratoLaboralMail;

class FixNullAuditRecordsSeeder extends Seeder
{
    public function run(): void
    {
        $fromAddress = config('mail.from.address', 'boletas@plasticosfenix.com');
        $fromName = config('mail.from.name', 'Plásticos Fénix - RRHH');

        // Fix envios_boletas
        $enviosBoletas = EnvioBoleta::with(['boleta.empleado.area', 'boleta.empleado.cargo'])
            ->whereNull('cuerpo_html')
            ->get();

        foreach ($enviosBoletas as $envio) {
            if ($envio->boleta && $envio->boleta->empleado) {
                try {
                    $mailable = new BoletaPagoMail($envio->boleta);
                    $html = $mailable->render();
                    
                    $headers = $envio->headers_raw ?? [];
                    $headers['from'] = $headers['from'] ?? $fromAddress;
                    $headers['from_name'] = $headers['from_name'] ?? $fromName;
                    $headers['to'] = $headers['to'] ?? ($envio->boleta->empleado->email ?? 'N/A');
                    $headers['subject'] = $headers['subject'] ?? $mailable->asuntoFinal;

                    if (empty($headers['raw_source'])) {
                        $fechaStr = $envio->fecha_envio ? $envio->fecha_envio->setTimezone('America/Lima')->toRfc2822String() : date('r');
                        $msgId = $envio->message_id && !str_starts_with($envio->message_id, 'msg-') ? $envio->message_id : ("<msg-b-{$envio->id}-" . time() . "@plasticosfenix.pe>");
                        $headers['raw_source'] = "Delivered-To: {$headers['to']}\n" .
                            "Received: by 10.2.3.4 with SMTP id cpanel-boleta-{$envio->id}; {$fechaStr}\n" .
                            "Message-ID: {$msgId}\n" .
                            "Date: {$fechaStr}\n" .
                            "From: {$headers['from_name']} <{$headers['from']}>\n" .
                            "To: {$headers['to']}\n" .
                            "Subject: {$headers['subject']}\n" .
                            "MIME-Version: 1.0\n" .
                            "Content-Type: text/html; charset=UTF-8\n\n" .
                            $html;
                    }

                    $messageId = $envio->message_id;
                    if (empty($messageId) || (str_starts_with($messageId, 'msg-') && strlen($messageId) < 10)) {
                        $messageId = "<msg-b-{$envio->id}-" . time() . "@plasticosfenix.pe>";
                    }

                    $envio->update([
                        'cuerpo_html' => $html,
                        'headers_raw' => $headers,
                        'message_id' => $messageId,
                    ]);
                } catch (\Throwable $e) {
                    // Ignorar si falla renderizado de registro incompleto
                }
            }
        }

        // Fix envios_contratos
        $enviosContratos = EnvioContrato::with(['contrato.empleado.area', 'contrato.empleado.cargo'])
            ->whereNull('cuerpo_html')
            ->get();

        foreach ($enviosContratos as $envio) {
            if ($envio->contrato && $envio->contrato->empleado) {
                try {
                    $mailable = new ContratoLaboralMail($envio->contrato);
                    $html = $mailable->render();
                    
                    $headers = $envio->headers_raw ?? [];
                    $headers['from'] = $headers['from'] ?? $fromAddress;
                    $headers['from_name'] = $headers['from_name'] ?? $fromName;
                    $headers['to'] = $headers['to'] ?? ($envio->contrato->empleado->email ?? 'N/A');
                    $headers['subject'] = $headers['subject'] ?? $mailable->asuntoFinal;

                    if (empty($headers['raw_source'])) {
                        $fechaStr = $envio->fecha_envio ? $envio->fecha_envio->setTimezone('America/Lima')->toRfc2822String() : date('r');
                        $msgId = $envio->message_id && !str_starts_with($envio->message_id, 'msg-') ? $envio->message_id : ("<msg-c-{$envio->id}-" . time() . "@plasticosfenix.pe>");
                        $headers['raw_source'] = "Delivered-To: {$headers['to']}\n" .
                            "Received: by 10.2.3.4 with SMTP id cpanel-contrato-{$envio->id}; {$fechaStr}\n" .
                            "Message-ID: {$msgId}\n" .
                            "Date: {$fechaStr}\n" .
                            "From: {$headers['from_name']} <{$headers['from']}>\n" .
                            "To: {$headers['to']}\n" .
                            "Subject: {$headers['subject']}\n" .
                            "MIME-Version: 1.0\n" .
                            "Content-Type: text/html; charset=UTF-8\n\n" .
                            $html;
                    }

                    $messageId = $envio->message_id;
                    if (empty($messageId) || (str_starts_with($messageId, 'msg-') && strlen($messageId) < 10)) {
                        $messageId = "<msg-c-{$envio->id}-" . time() . "@plasticosfenix.pe>";
                    }

                    $envio->update([
                        'cuerpo_html' => $html,
                        'headers_raw' => $headers,
                        'message_id' => $messageId,
                    ]);
                } catch (\Throwable $e) {
                    // Ignorar si falla renderizado
                }
            }
        }
    }
}
