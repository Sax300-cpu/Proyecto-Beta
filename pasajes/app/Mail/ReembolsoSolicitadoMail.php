<?php

namespace App\Mail;

use App\Models\Boleto;
use App\Models\ReembolsoSolicitud;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReembolsoSolicitadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Boleto $boleto,
        public readonly ReembolsoSolicitud $solicitud,
        public readonly bool $copiaPasajero = false,
    ) {
    }

    public function envelope(): Envelope
    {
        $ruta = $this->boleto->origen_abordaje . ' -> ' . $this->boleto->destino_desembarque;

        return new Envelope(
            subject: $this->copiaPasajero
                ? 'Solicitud de reembolso recibida - ' . $ruta
                : 'Nueva solicitud de reembolso - ' . $ruta,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reembolso-solicitado',
            with: [
                'boleto' => $this->boleto,
                'solicitud' => $this->solicitud,
                'copiaPasajero' => $this->copiaPasajero,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
