<?php

namespace App\Jobs;

use App\Mail\BoletaPagoMail;
use App\Models\Boleta;
use App\Models\Configuracion;
use App\Models\EnvioBoleta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SendBoletaEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $boletaId;
    public ?int $enviadoPorId;

    /**
     * Número de reintentos máximos del Job.
     */
    public int $tries = 3;

    /**
     * Tiempo límite de ejecución del Job (segundos).
     */
    public int $timeout = 120;

    public function __construct(int $boletaId, ?int $enviadoPorId = null)
    {
        $this->boletaId = $boletaId;
        $this->enviadoPorId = $enviadoPorId;
    }

    public function handle(): void
    {
        $boleta = Boleta::with(['empleado.area', 'empleado.cargo'])->find($this->boletaId);

        if (!$boleta) {
            return;
        }

        // Control de velocidad (Throttling) para SMTP cPanel
        $throttlePerMinute = (int) env('MAIL_THROTTLE_PER_MINUTE', 10);
        if ($throttlePerMinute > 0) {
            $sleepSeconds = (int) ceil(60 / $throttlePerMinute);
            sleep($sleepSeconds);
        }

        $fromAddress = config('mail.from.address', 'boletas@plasticosfenix.com');
        $fromName = config('mail.from.name', 'PLASTICOS FENIX - RRHH');
        $cuerpoHtml = null;

        try {
            // Verificar existencia del archivo PDF en la carpeta compartida
            if (!Storage::disk('boletas')->exists($boleta->ruta_pdf)) {
                throw new \Exception("El archivo PDF no existe en la ruta compartida: {$boleta->ruta_pdf}");
            }

            // Validar correo del empleado
            if (empty($boleta->empleado->email) || !filter_var($boleta->empleado->email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("El empleado '{$boleta->empleado->nombre_completo}' no tiene un correo electrónico válido asignado.");
            }

            // Cargar plantilla desde BD o fallback
            $asunto = Configuracion::obtener('mail_plantilla_asunto', 'Boleta de Pago - {periodo} - PLASTICOS FENIX');
            $cuerpo = Configuracion::obtener('mail_plantilla_cuerpo', '<p>Estimado(a) {nombre}, adjuntamos su boleta de pago del periodo {periodo}.</p>');

            $mailable = new BoletaPagoMail($boleta, $asunto, $cuerpo);
            $cuerpoHtml = $mailable->render();

            // Enviar correo vía Mailer SMTP
            $sentMessage = Mail::to($boleta->empleado->email)->send($mailable);

            $symfonySent = $sentMessage ? $sentMessage->getSymfonySentMessage() : null;
            $messageId = $symfonySent ? $symfonySent->getMessageId() : ($sentMessage ? $sentMessage->getMessageId() : ('<msg-' . $boleta->id . '-' . time() . '@plasticosfenix.pe>'));
            $rawSource = $symfonySent ? $symfonySent->toString() : null;

            $headersRaw = [
                'from' => $fromAddress,
                'from_name' => $fromName,
                'to' => $boleta->empleado->email,
                'subject' => $mailable->asuntoFinal ?? $asunto,
                'raw_source' => $rawSource,
            ];

            // Registrar envío exitoso en Auditoría
            EnvioBoleta::create([
                'boleta_id' => $boleta->id,
                'fecha_envio' => now(),
                'estado_envio' => 'exito',
                'message_id' => $messageId,
                'headers_raw' => $headersRaw,
                'cuerpo_html' => $cuerpoHtml,
                'mensaje_error' => null,
                'enviado_por' => $this->enviadoPorId,
            ]);

            // Actualizar estado de la boleta
            $boleta->update(['estado' => 'enviada']);

        } catch (Throwable $e) {
            $headersRaw = [
                'from' => $fromAddress,
                'from_name' => $fromName,
                'to' => $boleta->empleado->email ?? 'N/A',
                'subject' => isset($mailable) ? ($mailable->asuntoFinal ?? 'Boleta de Pago') : 'Boleta de Pago',
                'raw_source' => null,
            ];

            // Registrar error en Auditoría
            EnvioBoleta::create([
                'boleta_id' => $boleta->id,
                'fecha_envio' => now(),
                'estado_envio' => 'fallido',
                'message_id' => '<err-' . $boleta->id . '-' . time() . '@plasticosfenix.pe>',
                'headers_raw' => $headersRaw,
                'cuerpo_html' => $cuerpoHtml,
                'mensaje_error' => substr($e->getMessage(), 0, 1000),
                'enviado_por' => $this->enviadoPorId,
            ]);

            // Actualizar estado de la boleta a error
            $boleta->update(['estado' => 'error']);

            // Relanzar la excepción para que Laravel registre el fallo en la cola si aplica
            throw $e;
        }
    }
}
