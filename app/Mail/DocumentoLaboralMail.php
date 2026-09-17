<?php

namespace App\Mail;

use App\Models\DocumentoLaboral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentoLaboralMail extends Mailable
{
    use Queueable, SerializesModels;

    public DocumentoLaboral $documento;
    public string $asuntoFinal;

    public function __construct(DocumentoLaboral $documento)
    {
        $this->documento = $documento->loadMissing(['empleado.area', 'empleado.cargo']);

        $tipoStr = $this->documento->tipo_nombre;
        $empleadoNom = $this->documento->empleado->nombre_completo;
        $this->asuntoFinal = "{$tipoStr} - {$empleadoNom} - PLASTICOS FENIX";
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
            view: 'emails.documento_laboral',
            with: [
                'documento' => $this->documento,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->documento->existeArchivo()) {
            $attachments[] = Attachment::fromStorageDisk('boletas', $this->documento->ruta_pdf)
                ->as("{$this->documento->tipo_documento}_{$this->documento->empleado->dni}.pdf")
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
