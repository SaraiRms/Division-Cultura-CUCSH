<?php

namespace App\Mail;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Aviso a la persona que envió la participación cuando es publicada en el sitio.
class AportacionPublicada extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contribution $aportacion)
    {
    }

    public function envelope(): Envelope
    {
        $contacto = config('site.contacto_datos.correo');

        return new Envelope(
            subject: '¡Tu participación fue publicada!',
            replyTo: $contacto ? [new Address($contacto)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.aportacion-publicada');
    }
}