<?php

namespace App\Mail;

use App\Models\Boleta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BoletaPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Boleta $boleta;
    public string $asuntoFinal;
    public ?string $cuerpoHtmlFinal;

    public function __construct(Boleta $boleta, ?string $asunto = null, ?string $cuerpoHtml = null)
    {
        $this->boleta = $boleta->loadMissing(['empleado.area', 'empleado.cargo']);

        // Reemplazar variables dinámicas en el asunto y cuerpo si son provistos
        $variables = [
            '{nombre}' => $this->boleta->empleado->nombre_completo,
            '{dni}' => $this->boleta->empleado->dni,
            '{periodo}' => $this->boleta->periodo_formateado,
            '{periodo_mes}' => $this->boleta->nombre_mes,
            '{periodo_anio}' => $this->boleta->periodo_anio,
            '{cargo}' => $this->boleta->empleado->cargo->nombre ?? 'N/A',
            '{area}' => $this->boleta->empleado->area->nombre ?? 'N/A',
        ];

        $asuntoBase = $asunto ?? 'Boleta de Pago - {periodo} - PLASTICOS FENIX';
        $this->asuntoFinal = str_replace(array_keys($variables), array_values($variables), $asuntoBase);

        if ($cuerpoHtml) {
            $this->cuerpoHtmlFinal = str_replace(array_keys($variables), array_values($variables), $cuerpoHtml);
        } else {
            $this->cuerpoHtmlFinal = null;
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->asuntoFinal,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.boleta_pago',
            with: [
                'boleta' => $this->boleta,
                'cuerpoCustom' => $this->cuerpoHtmlFinal,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->boleta->existeArchivo()) {
            $attachments[] = Attachment::fromStorageDisk('boletas', $this->boleta->ruta_pdf)
                ->as("Boleta_{$this->boleta->empleado->dni}.pdf")
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
