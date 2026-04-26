<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\HojaRuta;
use App\Models\Asiento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ChoferController extends Controller
{
    /**
     * Panel principal del chofer con el escáner QR.
     */
    public function escaner()
    {
        return view(\'chofer.escaner\');
    }

    /**
     * Ruta de validación de QR (accedida cuando el chofer escanea con cámara).
     * Redirige al escáner con el código pre-rellenado.
     */
    public function validarQr(string $qrCode)
    {
        $boleto = Boleto::with([
            \'hojaRuta.frecuencia.ruta.cooperativa\',
            \'asiento\',
            \'user\',
        ])->where(\'qr_code\', $qrCode)->first();

        if (! $boleto) {
            abort(404, \'Código QR inválido.\');
        }

        // Solo el chofer asignado a la hoja de ruta puede validar
        if ($boleto->hojaRuta->chofer_id !== auth()->id()
            && ! auth()->user()->hasRole(\'Admin\')) {
            abort(403, \'No eres el chofer asignado a este viaje.\');
        }

        return view(\'chofer.validar-qr\', compact(\'boleto\'));
    }

    /**
     * Procesar la acción del chofer (Abordado o No Show)
     */
    public function accionQr(Boleto $boleto, Request $request)
    {
        $request->validate([\'accion\' => \'required|in:abordado,noshow\']);

        // Solo el chofer asignado puede modificar
        if ($boleto->hojaRuta->chofer_id !== auth()->id() && !auth()->user()->hasRole(\'Admin\')) {
            abort(403);
        }

        if ($request->accion === \'abordado\') {
            $boleto->update([\'estado\' => \'Abordado\']);
            return back()->with(\'success\', \'Boleto marcado como abordado.\');
        }

        $boleto->update([\'estado\' => \'No Show\']);
        return back()->with(\'info\', \'Boleto marcado como No Show.\');
    }

    /**
     * Interfaz para vender boletos en ruta.
     */
    public function venderEnRuta(HojaRuta $hojaRuta)
    {
        // Regla 3 y 4: Chofer puede vender en ruta si ya partió y no es ruta directa
        if (!auth()->user()->hasRole(\'Admin\') && (!$hojaRuta->yaPartio() || $hojaRuta->frecuencia->es_directa)) {
            abort(403, \'No puedes vender boletos en ruta para esta frecuencia en este momento.\');
        }

        $asientosDisponibles = $hojaRuta->asientosDisponibles();
        $paradas = $hojaRuta->frecuencia->paradas()->get();

        return view(\'chofer.vender-en-ruta\', compact(\'hojaRuta\', \'asientosDisponibles\', \'paradas\'));
    }

    /**
     * Procesa la venta de un boleto en ruta.
     */
    public function storeVentaEnRuta(Request $request, HojaRuta $hojaRuta)
    {
        // Validaciones basadas en la especificación
        if (!auth()->user()->hasRole(\'Admin\') && (!$hojaRuta->yaPartio() || $hojaRuta->frecuencia->es_directa)) {
            throw ValidationException::withMessages([
                \'general\' => \'No puedes vender boletos en ruta para esta frecuencia en este momento.\',
            ]);
        }

        $data = $request->validate([
            \'asiento_id\' => [\'required\', \'exists:asientos,id\', function ($attribute, $value, $fail) use ($hojaRuta) {
                if (Boleto::where(\'hoja_ruta_id\', $hojaRuta->id)->where(\'asiento_id\', $value)->exists()) {
                    $fail(\'El asiento ya está ocupado.\');
                }
            }],
            \'nombre_pasajero\' => \'required|string|max:255\',
            \'cedula_pasajero\' => \'required|string|max:13\',
            \'tipo_pasajero\' => \'required|in:Normal,Niño,Tercera Edad,Discapacitado\',
            \'origen_abordaje\' => \'required|string|max:100\',
            \'destino_desembarque\' => \'required|string|max:100\',
            \'precio\' => \'required|numeric|min:0.01\',
        ]);

        DB::transaction(function () use ($data, $hojaRuta) {
            $boleto = Boleto::create(array_merge($data, [
                \'hoja_ruta_id\' => $hojaRuta->id,
                \'vendido_por\' => auth()->id(),
                \'estado\' => \'Abordado\', // Se asume abordado al vender en ruta
                \'vendido_en_ruta\' => true,
                \'qr_code\' => \'RUTA-\' . uniqid(), // QR simple para boletos en ruta
            ]));
        });

        return back()->with(\'success\', \'Boleto vendido en ruta exitosamente.\');
    }
}