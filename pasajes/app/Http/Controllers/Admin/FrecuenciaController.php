<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frecuencia;
use App\Models\Ruta;
use Illuminate\Http\Request;

class FrecuenciaController extends Controller
{
    public function index()
    {
        $cooperativa_id = auth()->user()->cooperativa_id;
        $frecuencias = Frecuencia::with('ruta')
            ->whereHas('ruta', function ($query) use ($cooperativa_id) {
                $query->where('cooperativa_id', $cooperativa_id);
            })->paginate(15);
            
        $rutas = Ruta::where('cooperativa_id', $cooperativa_id)->where('activa', true)->get();

        $latestFrecuencia = Frecuencia::latest('id')->first();
        $nextNumber = $latestFrecuencia ? $latestFrecuencia->id + 1 : 1;
        $nextResolucion = 'ANT-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('admin.frecuencias.index', compact('frecuencias', 'rutas', 'nextResolucion'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'hora_salida' => 'required|date_format:H:i',
            'resolucion_ant' => 'nullable|string|max:100',
            'es_directa' => 'boolean',
        ]);

        $data['es_directa'] = $request->has('es_directa');
        $data['activa'] = true;

        // Validar que la ruta pertenezca a la cooperativa
        $ruta = Ruta::findOrFail($data['ruta_id']);
        if ($ruta->cooperativa_id !== auth()->user()->cooperativa_id) abort(403);

        Frecuencia::create($data);

        return back()->with('success', 'Frecuencia asignada exitosamente.');
    }

    public function update(Request $request, Frecuencia $frecuencia)
    {
        if ($frecuencia->ruta->cooperativa_id !== auth()->user()->cooperativa_id) abort(403);

        $data = $request->validate([
            'hora_salida' => 'required|date_format:H:i:s', // HTML time input might send seconds
            'resolucion_ant' => 'nullable|string|max:100',
            'es_directa' => 'boolean',
            'activa' => 'boolean',
        ]);

        // Truncate seconds if needed or leave it, DB handles time format.
        $data['es_directa'] = $request->has('es_directa');
        $data['activa'] = $request->has('activa');
        
        $frecuencia->update($data);

        return back()->with('success', 'Frecuencia actualizada exitosamente.');
    }

    public function destroy(Frecuencia $frecuencia)
    {
        if ($frecuencia->ruta->cooperativa_id !== auth()->user()->cooperativa_id) abort(403);

        try {
            $frecuencia->delete();
            return back()->with('success', 'Frecuencia eliminada.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar la frecuencia porque tiene hojas de ruta u operaciones asignadas.');
        }
    }
}
