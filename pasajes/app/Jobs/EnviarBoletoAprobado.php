<?php

namespace App\Jobs;

use App\Models\Boleto;
use App\Notifications\BoletoAprobadoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarBoletoAprobado implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(public readonly Boleto $boleto) {}

    public function handle(): void
    {
        $destinatario = $this->boleto->user;

        if (! $destinatario || ! $destinatario->email) {
            return;
        }

        $destinatario->notify(new BoletoAprobadoNotification($this->boleto));
    }
}
