<?php

namespace App\Http\Controllers;

use App\Mail\ReembolsoSolicitadoMail;
use App\Models\Boleto;
use App\Models\ReembolsoSolicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReembolsoController extends Controller
{
    public function store(Request $request, Boleto $boleto): RedirectResponse
    {
        abort_unless((int) $boleto->user_id === (int) auth()->id(), 403);

        $request->validate([
            'motivo' => ['required', 'string', 'min:10', 'max:1200'],
        ]);

        $solicitud = ReembolsoSolicitud::updateOrCreate(
            ['boleto_id' => $boleto->id],
            [
                'user_id' => auth()->id(),
                'motivo' => trim((string) $request->input('motivo')),
                'estado' => 'Pendiente',
            ]
        );

        $boleto->loadMissing('hojaRuta.frecuencia.ruta.cooperativa');
        $emailCooperativa = $boleto->hojaRuta->frecuencia->ruta->cooperativa->email_soporte
            ?? $boleto->hojaRuta->frecuencia->ruta->cooperativa->email;

        if ($emailCooperativa) {
            Mail::to($emailCooperativa)->send(new ReembolsoSolicitadoMail($boleto, $solicitud, false));
        }

        if (auth()->user()?->email) {
            Mail::to(auth()->user()->email)->send(new ReembolsoSolicitadoMail($boleto, $solicitud, true));
        }

        return back()->with('success', 'Solicitud de reembolso enviada. Debes acercarte a oficina para finalizar el proceso.');
    }
}
