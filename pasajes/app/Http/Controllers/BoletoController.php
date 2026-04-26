<?php

namespace App\Http\Controllers;

use App\Models\Boleto;
use App\Models\HojaRuta;
use Illuminate\Http\Request;

class BoletoController extends Controller
{
    /**
     * Historial de compras del usuario autenticado.
     */
    public function index()
    {
        $boletos = Boleto::with([
            'hojaRuta.frecuencia.ruta.cooperativa',
            'asiento',
            'comprobante',
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

        return view('boletos.index', compact('boletos'));
    }

    /**
     * Pantalla de selección de asientos para una hoja de ruta.
     */
    public function comprar(HojaRuta $hojaRuta)
    {
        // Solo hojas de ruta habilitadas para la venta
        abort_if($hojaRuta->estado === 'Cancelada', 404, 'Esta frecuencia fue cancelada.');
        abort_if($hojaRuta->estado === 'Completada', 404, 'Esta frecuencia ya finalizó.');

        $hojaRuta->load([
            'frecuencia.ruta.cooperativa',
            'bus.categoria',
            'bus.asientos',
        ]);

        return view('boletos.comprar', compact('hojaRuta'));
    }

    /**
     * Ver detalle de un boleto (solo el propietario o staff).
     */
    public function ver(Boleto $boleto)
    {
        $this->autorizarVer($boleto);

        $boleto->load([
            'hojaRuta.frecuencia.ruta.cooperativa',
            'asiento',
            'hojaRuta.bus',
            'comprobante',
        ]);

        return view('boletos.ver', compact('boleto'));
    }

    private function autorizarVer(Boleto $boleto): void
    {
        $user = auth()->user();

        // Staff puede ver todo
        if ($user->hasAnyRole(['Admin', 'Oficinista', 'Chofer'])) {
            return;
        }

        // Usuario solo ve sus propios boletos
        abort_unless($boleto->user_id === $user->id, 403, 'No tienes acceso a este boleto.');
    }
}
