<?php

namespace App\Notifications;

use App\Models\Boleto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BoletoAprobadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Boleto $boleto) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $boleto   = $this->boleto;
        $hojaRuta = $boleto->hojaRuta;
        $ruta     = $hojaRuta->frecuencia->ruta;
        $coop     = $ruta->cooperativa;

        return (new MailMessage)
            ->subject("✅ Boleto Aprobado — {$ruta->origen} → {$ruta->destino}")
            ->greeting("Hola, {$notifiable->name}!")
            ->line("Tu boleto para el viaje **{$ruta->origen} → {$ruta->destino}** ha sido **aprobado**.")
            ->line("**Fecha:** " . $hojaRuta->fecha->format('d/m/Y'))
            ->line("**Hora de salida:** " . $hojaRuta->frecuencia->hora_salida)
            ->line("**Asiento:** {$boleto->asiento->numero} ({$boleto->asiento->tipo})")
            ->line("**Pasajero:** {$boleto->nombre_pasajero}")
            ->line("**Cédula:** {$boleto->cedula_pasajero}")
            ->line("**Precio pagado:** \${$boleto->precio}")
            ->action('Ver mi boleto digital (QR)', route('boleto.ver', $boleto->id))
            ->line("Por favor presenta el código QR al subir al bus.")
            ->line("**{$coop->nombre}** — {$coop->telefono}");
    }
}
