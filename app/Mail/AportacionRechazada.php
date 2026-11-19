<?php

namespace App\Mail;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Aviso a la persona que envió la participación cuando es rechazada.
class AportacionRechazada extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Contribution $aportacion)
    {
    }

    public function envelope(): Envelope
    {
        // Si la persona responde el correo, le llega al contacto de la División
        $contacto = config('site.contacto_datos.correo');

        return new Envelope(
            subject: 'Sobre tu participación en la División de Estudios de la Cultura',
            replyTo: $contacto ? [new Address($contacto)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.aportacion-rechazada');
    }
}