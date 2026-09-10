<?php

namespace App\Jobs;

use App\Mail\ContratoLaboralMail;
use App\Models\Contrato;
use App\Models\EnvioContrato;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SendContratoEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $contratoId;
    public ?int $enviadoPorId;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(int $contratoId, ?int $enviadoPorId = null)
    {
        $this->contratoId = $contratoId;
        $this->enviadoPorId = $enviadoPorId;
    }

    public function handle(): void
    {
        $contrato = Contrato::with(['empleado.area', 'empleado.cargo'])->find($this->contratoId);

        if (!$contrato) {
            return;
        }

        // Control de velocidad (Throttling) para SMTP cPanel
        $throttlePerMinute = (int) env('MAIL_THROTTLE_PER_MINUTE', 10);
        if ($throttlePerMinute > 0) {
            $sleepSeconds = (int) ceil(60 / $throttlePerMinute);
            sleep($sleepSeconds);
        }

        $fromAddress = config('mail.from.address', 'contratos@plasticosfenix.com');
        $fromName = config('mail.from.name', 'PLASTICOS FENIX - RRHH');
        $cuerpoHtml = null;

        try {
            // Verificar existencia del archivo PDF en la carpeta compartida/privada
            if (!Storage::disk('boletas')->exists($contrato->ruta_pdf)) {
                throw new \Exception("El archivo PDF del contrato no existe en el almacenamiento: {$contrato->ruta_pdf}");
            }

            // Validar correo del empleado
            if (empty($contrato->empleado->email) || !filter_var($contrato->empleado->email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("El empleado '{$contrato->empleado->nombre_completo}' no tiene un correo electrónico válido.");
            }

            $mailable = new ContratoLaboralMail($contrato);
            $cuerpoHtml = $mailable->render();

            // Enviar correo vía Mailer SMTP
            $sentMessage = Mail::to($contrato->empleado->email)->send($mailable);

            $symfonySent = $sentMessage ? $sentMessage->getSymfonySentMessage() : null;
            $messageId = $symfonySent ? $symfonySent->getMessageId() : ($sentMessage ? $sentMessage->getMessageId() : ('<msg-' . $contrato->id . '-' . time() . '@plasticosfenix.pe>'));
            $rawSource = $symfonySent ? $symfonySent->toString() : null;

            $headersRaw = [
                'from' => $fromAddress,
                'from_name' => $fromName,
                'to' => $contrato->empleado->email,
                'subject' => $mailable->asuntoFinal,
                'raw_source' => $rawSource,
            ];

            // Registrar envío exitoso en Auditoría
            EnvioContrato::create([
                'contrato_id' => $contrato->id,
                'fecha_envio' => now(),
                'estado_envio' => 'exito',
                'message_id' => $messageId,
                'headers_raw' => $headersRaw,
                'cuerpo_html' => $cuerpoHtml,
                'mensaje_error' => null,
                'enviado_por' => $this->enviadoPorId,
            ]);

            // Actualizar estado del envío en contrato
            $contrato->update(['estado_envio' => 'enviado']);

        } catch (Throwable $e) {
            $headersRaw = [
                'from' => $fromAddress,
                'from_name' => $fromName,
                'to' => $contrato->empleado->email ?? 'N/A',
                'subject' => isset($mailable) ? $mailable->asuntoFinal : 'Contrato Laboral',
                'raw_source' => null,
            ];

            // Registrar error en Auditoría
            EnvioContrato::create([
                'contrato_id' => $contrato->id,
                'fecha_envio' => now(),
                'estado_envio' => 'fallido',
                'message_id' => '<err-' . $contrato->id . '-' . time() . '@plasticosfenix.pe>',
                'headers_raw' => $headersRaw,
                'cuerpo_html' => $cuerpoHtml,
                'mensaje_error' => substr($e->getMessage(), 0, 1000),
                'enviado_por' => $this->enviadoPorId,
            ]);

            // Actualizar estado a error
            $contrato->update(['estado_envio' => 'error']);

            throw $e;
        }
    }
}
