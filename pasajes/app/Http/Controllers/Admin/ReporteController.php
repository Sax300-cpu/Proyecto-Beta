<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleto;
use App\Models\HojaRuta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->endOfMonth()->format('Y-m-d'));

        // Ventas por día
        $ventasPorDia = Boleto::whereBetween('created_at', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59'])
            ->where('estado', 'Validado')
            ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('SUM(precio) as total'))
            ->groupBy('fecha')
            ->get();

        // Ocupación por ruta
        $ocupacionRutas = HojaRuta::whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->with(['frecuencia.ruta', 'boletos'])
            ->get()
            ->groupBy(function ($hoja) {
                return $hoja->frecuencia->ruta->origen . ' - ' . $hoja->frecuencia->ruta->destino;
            })
            ->map(function ($hojas, $ruta) {
                $totalBoletos = $hojas->sum(function ($hoja) {
                    return $hoja->boletos->count();
                });
                $totalCapacidad = $hojas->sum(function ($hoja) {
                    return $hoja->bus->capacidad_asientos;
                });
                return [
                    'ruta' => $ruta,
                    'boletos' => $totalBoletos,
                    'capacidad' => $totalCapacidad,
                    'porcentaje' => $totalCapacidad > 0 ? round(($totalBoletos / $totalCapacidad) * 100, 2) : 0,
                ];
            });

        return view('admin.reportes.index', compact('ventasPorDia', 'ocupacionRutas', 'fechaInicio', 'fechaFin'));
    }
}