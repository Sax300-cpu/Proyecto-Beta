<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        $cooperativa_id = auth()->user()->cooperativa_id;
        $rutas = Ruta::where('cooperativa_id', $cooperativa_id)->paginate(15);
        return view('admin.rutas.index', compact('rutas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'origen' => 'required|string|max:100',
            'destino' => 'required|string|max:100',
        ]);

        $data['cooperativa_id'] = auth()->user()->cooperativa_id;
        $data['activa'] = true;

        Ruta::create($data);

        return back()->with('success', 'Ruta creada exitosamente.');
    }

    public function update(Request $request, Ruta $ruta)
    {
        if ($ruta->cooperativa_id !== auth()->user()->cooperativa_id) abort(403);

        $data = $request->validate([
            'origen' => 'required|string|max:100',
            'destino' => 'required|string|max:100',
            'activa' => 'boolean',
        ]);

        $data['activa'] = $request->has('activa');
        $ruta->update($data);

        return back()->with('success', 'Ruta actualizada exitosamente.');
    }

    public function destroy(Ruta $ruta)
    {
        if ($ruta->cooperativa_id !== auth()->user()->cooperativa_id) abort(403);

        try {
            $ruta->delete();
            return back()->with('success', 'Ruta eliminada.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar la ruta porque tiene frecuencias asignadas.');
        }
    }
}
