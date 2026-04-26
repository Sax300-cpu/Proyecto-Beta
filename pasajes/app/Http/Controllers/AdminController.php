<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHojaRutaRequest;
use App\Models\Bus;
use App\Models\CategoriaBus;
use App\Models\Cooperativa;
use App\Models\Frecuencia;
use App\Models\HojaRuta;
use App\Models\Ruta;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'boletos_hoy'        => \App\Models\Boleto::whereDate('created_at', today())->count(),
            'pendientes'         => \App\Models\Comprobante::where('estado', 'Pendiente')->count() ?? 0,
            'hojas_hoy'         => HojaRuta::where('fecha', today())->count(),
            'ingresos_mes'      => \App\Models\Boleto::whereMonth('created_at', now()->month)
                                    ->where('estado', 'Validado')->sum('precio'),
        ];

        $hojasHoy = HojaRuta::with(['frecuencia.ruta', 'bus', 'boletos'])
            ->where('fecha', today())
            ->get();

        return view('admin.dashboard', compact('stats', 'hojasHoy'));
    }

    public function hojasRuta()
    {
        $hojas = HojaRuta::with(['frecuencia.ruta', 'bus', 'chofer'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);

        $frecuencias = Frecuencia::habilitadas()->with('ruta')->get();
        $buses       = Bus::where('habilitado', true)->get();
        $choferes    = User::role('Chofer')->get();

        return view('admin.hojas-ruta', compact('hojas', 'frecuencias', 'buses', 'choferes'));
    }

    public function storeHojaRuta(StoreHojaRutaRequest $request)
    {
        HojaRuta::create($request->validated());

        return back()->with('success', 'Hoja de ruta creada exitosamente.');
    }

    public function cambiarBusHojaRuta(HojaRuta $hojaRuta, Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
        ]);

        // Regla 8: Verificar que el bus esté habilitado
        $bus = Bus::findOrFail($request->bus_id);
        if (!$bus->habilitado) {
            return back()->with('error', 'El bus seleccionado no está operativo.');
        }

        // Regla 9: Verificar que el bus no cubra dos frecuencias simultáneas
        $existeCruce = HojaRuta::where('bus_id', $bus->id)
            ->where('fecha', $hojaRuta->fecha)
            ->where('id', '!=', $hojaRuta->id)
            ->whereHas('frecuencia', function($q) use ($hojaRuta) {
                $q->where('hora_salida', $hojaRuta->frecuencia->hora_salida);
            })->exists();

        if ($existeCruce) {
            return back()->with('error', 'El bus ya está asignado a otra frecuencia en este horario.');
        }

        $hojaRuta->update(['bus_id' => $bus->id]);

        // Aquí se podría disparar una notificación a los pasajeros

        return back()->with('success', 'Bus de la hoja de ruta actualizado exitosamente.');
    }

    public function cambiarEstadoHojaRuta(HojaRuta $hojaRuta, Request $request)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,En Ruta,Completada,Cancelada',
        ]);

        // Si cambia a "En Ruta", registra hora de partida real
        if ($request->estado === 'En Ruta' && ! $hojaRuta->hora_partida_real) {
            $hojaRuta->hora_partida_real = now();
        }

        $hojaRuta->update(['estado' => $request->estado]);

        return back()->with('success', "Estado actualizado a '{$request->estado}'.");
    }

    public function usuarios()
    {
        $usuarios = User::with('roles', 'cooperativa')->paginate(20);
        return view('admin.usuarios', compact('usuarios'));
    }

    public function cooperativa()
    {
        $cooperativa = auth()->user()->cooperativa;
        return view('admin.cooperativa', compact('cooperativa'));
    }

    public function updateCooperativa(Request $request)
    {
        $data = $request->validate([
            'nombre'          => 'required|string|max:255',
            'telefono'        => 'nullable|string|max:20',
            'email'           => 'nullable|email',
            'direccion'       => 'nullable|string|max:255',
            'cuenta_bancaria' => 'nullable|string|max:50',
            'banco'           => 'nullable|string|max:100',
            'titular_cuenta'  => 'nullable|string|max:255',
            'whatsapp'        => 'nullable|string|max:20',
            'email_soporte'   => 'nullable|email',
            'color_primario'  => 'nullable|string|size:7',
            'color_secundario'=> 'nullable|string|size:7',
            'facebook_url'    => 'nullable|url|max:255',
            'instagram_url'   => 'nullable|url|max:255',
            'logo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if (auth()->user()->cooperativa->logo_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(auth()->user()->cooperativa->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('logos', 'public');
        }

        auth()->user()->cooperativa->update($data);

        return back()->with('success', 'Datos de la cooperativa actualizados.');
    }
}
