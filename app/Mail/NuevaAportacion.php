<?php

namespace App\Mail;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaAportacion extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contribution $aportacion)
    {
    }

    public function envelope(): Envelope
    {
        $tipo = $this->aportacion->author_type === 'alumno' ? 'estudiante' : 'académico';

        return new Envelope(
            subject: "Nueva participación de {$tipo} recibida en el sitio",
            replyTo: [new Address($this->aportacion->contact_email)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.nueva-aportacion');
    }

    // El PDF va adjunto (se toma de la carpeta privada)
    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('local', $this->aportacion->pdf_path)
                ->as('participacion-' . $this->aportacion->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}