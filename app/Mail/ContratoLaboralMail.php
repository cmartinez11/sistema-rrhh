<?php

namespace App\Mail;

use App\Models\Contrato;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContratoLaboralMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contrato $contrato;
    public string $asuntoFinal;

    public function __construct(Contrato $contrato)
    {
        $this->contrato = $contrato->loadMissing(['empleado.area', 'empleado.cargo']);
        $this->asuntoFinal = "Contrato Laboral - {$this->contrato->empleado->nombre_completo} - PLASTICOS FENIX";
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
            view: 'emails.contrato_laboral',
            with: [
                'contrato' => $this->contrato,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->contrato->existeArchivo()) {
            $attachments[] = Attachment::fromStorageDisk('boletas', $this->contrato->ruta_pdf)
                ->as("Contrato_{$this->contrato->empleado->dni}.pdf")
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
