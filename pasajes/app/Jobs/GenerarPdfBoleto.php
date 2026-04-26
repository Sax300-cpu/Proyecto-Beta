<?php

namespace App\Jobs;

use App\Models\Boleto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerarPdfBoleto implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly Boleto $boleto) {}

    public function handle(): void
    {
        $boleto = $this->boleto->load([
            'hojaRuta.frecuencia.ruta.cooperativa',
            'asiento',
            'hojaRuta.bus',
        ]);

        $pdf = Pdf::loadView('pdf.boleto', ['boleto' => $boleto]);

        $ruta = "boletos/{$boleto->qr_code}.pdf";
        Storage::disk('public')->put($ruta, $pdf->output());

        $boleto->update(['pdf_url' => $ruta]);
    }
}
