<?php

namespace App\Jobs;

use App\Mail\DocumentoLaboralMail;
use App\Models\DocumentoLaboral;
use App\Models\EnvioDocumento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SendDocumentoLaboralEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $documentoId;
    public ?int $enviadoPorId;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(int $documentoId, ?int $enviadoPorId = null)
    {
        $this->documentoId = $documentoId;
        $this->enviadoPorId = $enviadoPorId;
    }

    public function handle(): void
    {
        $documento = DocumentoLaboral::with(['empleado.area', 'empleado.cargo'])->find($this->documentoId);

        if (!$documento) {
            return;
        }

        // Control de velocidad (Throttling) para SMTP cPanel
        $throttlePerMinute = (int) env('MAIL_THROTTLE_PER_MINUTE', 10);
        if ($throttlePerMinute > 0) {
            $sleepSeconds = (int) ceil(60 / $throttlePerMinute);
            sleep($sleepSeconds);
        }

        $fromAddress = config('mail.from.address', 'notificaciones@plasticosfenix.com');
        $fromName = config('mail.from.name', 'PLASTICOS FENIX - RRHH');
        $cuerpoHtml = null;

        try {
            // Verificar existencia del archivo PDF en el almacenamiento
            if (!Storage::disk('boletas')->exists($documento->ruta_pdf)) {
                throw new \Exception("El archivo PDF del documento no existe en el almacenamiento: {$documento->ruta_pdf}");
            }

            // Validar correo del empleado
            if (empty($documento->empleado->email) || !filter_var($documento->empleado->email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("El empleado '{$documento->empleado->nombre_completo}' no tiene un correo electrónico válido.");
            }

            $mailable = new DocumentoLaboralMail($documento);
            $cuerpoHtml = $mailable->render();

            // Enviar correo vía Mailer SMTP
            $sentMessage = Mail::to($documento->empleado->email)->send($mailable);

            $symfonySent = $sentMessage ? $sentMessage->getSymfonySentMessage() : null;
            $messageId = $symfonySent ? $symfonySent->getMessageId() : ($sentMessage ? $sentMessage->getMessageId() : ('<msg-doc-' . $documento->id . '-' . time() . '@plasticosfenix.pe>'));
            $rawSource = $symfonySent ? $symfonySent->toString() : null;

            $headersRaw = [
                'from' => $fromAddress,
                'from_name' => $fromName,
                'to' => $documento->empleado->email,
                'subject' => $mailable->asuntoFinal,
                'raw_source' => $rawSource,
            ];

            // Registrar envío exitoso en Auditoría
            EnvioDocumento::create([
                'documento_id' => $documento->id,
                'fecha_envio' => now(),
                'estado_envio' => 'exito',
                'message_id' => $messageId,
                'headers_raw' => $headersRaw,
                'cuerpo_html' => $cuerpoHtml,
                'mensaje_error' => null,
                'enviado_por' => $this->enviadoPorId,
            ]);

            // Actualizar estado del envío en el documento
            $documento->update(['estado_envio' => 'enviado']);

        } catch (Throwable $e) {
            $headersRaw = [
                'from' => $fromAddress,
                'from_name' => $fromName,
                'to' => $documento->empleado->email ?? 'N/A',
                'subject' => isset($mailable) ? $mailable->asuntoFinal : $documento->tipo_nombre,
                'raw_source' => null,
            ];

            // Registrar error en Auditoría
            EnvioDocumento::create([
                'documento_id' => $documento->id,
                'fecha_envio' => now(),
                'estado_envio' => 'fallido',
                'message_id' => '<err-doc-' . $documento->id . '-' . time() . '@plasticosfenix.pe>',
                'headers_raw' => $headersRaw,
                'cuerpo_html' => $cuerpoHtml,
                'mensaje_error' => substr($e->getMessage(), 0, 1000),
                'enviado_por' => $this->enviadoPorId,
            ]);

            // Actualizar estado a error
            $documento->update(['estado_envio' => 'error']);

            throw $e;
        }
    }
}
