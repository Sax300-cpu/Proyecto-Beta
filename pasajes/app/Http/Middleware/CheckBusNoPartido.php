<?php

namespace App\Http\Middleware;

use App\Models\HojaRuta;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Regla de Negocio crítica:
 * - Si el bus ya partió → Oficinista NO puede vender (403).
 * - Si el bus ya partió → Chofer SÍ puede vender (continúa).
 * - Si el usuario es Admin → siempre puede continuar.
 */
class CheckBusNoPartido
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admin siempre puede
        if ($user->hasRole('Admin')) {
            return $next($request);
        }

        // Chofer siempre puede vender (incluso en ruta)
        if ($user->hasRole('Chofer')) {
            return $next($request);
        }

        // Para Oficinista y Usuario_Final: verificar estado del bus
        $routeParam = $request->route('hojaRuta');
        $hojaRuta = $routeParam instanceof HojaRuta 
            ? $routeParam 
            : ($routeParam ? HojaRuta::find($routeParam) : null);

        if (!$hojaRuta && $request->input('hoja_ruta_id')) {
            $hojaRuta = HojaRuta::find($request->input('hoja_ruta_id'));
        }

        if ($hojaRuta && $hojaRuta->yaPartio()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'El bus ya partió. No se pueden vender boletos para esta frecuencia.',
                    ], Response::HTTP_FORBIDDEN);
                }

                return redirect()->back()->withErrors([
                    'bus' => 'El bus ya partió. No es posible vender boletos para esta salida.',
                ]);
        }

        return $next($request);
    }
}
