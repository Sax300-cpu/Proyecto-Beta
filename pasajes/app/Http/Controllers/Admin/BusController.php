<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\CategoriaBus;
use App\Models\Asiento;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $cooperativa_id = auth()->user()->cooperativa_id;
        $buses = Bus::with('categoria')
            ->where('cooperativa_id', $cooperativa_id)
            ->paginate(15);
            
        $categorias = CategoriaBus::all();

        return view('admin.buses.index', compact('buses', 'categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_disco' => 'required|string|max:50',
            'placa' => 'required|string|max:20|unique:buses,placa',
            'categoria_bus_id' => 'required|exists:categorias_bus,id',
            'capacidad_asientos' => 'required|integer|min:10|max:100',
            'marca_chasis' => 'nullable|string|max:100',
            'carroceria' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto_url'] = $request->file('foto')->store('buses', 'public');
        }

        $data['cooperativa_id'] = auth()->user()->cooperativa_id;
        $data['habilitado'] = true;

        $bus = Bus::create($data);

        // Crear asientos por defecto (1 piso, tipo ventana/pasillo intercalado)
        for ($i = 1; $i <= $bus->capacidad_asientos; $i++) {
            $col = $i % 4;
            $tipo = in_array($col, [1, 0]) ? 'Ventana' : 'Pasillo';
            if ($i > $bus->capacidad_asientos - 4) $tipo = 'Fondo';
            
            Asiento::create([
                'bus_id' => $bus->id,
                'numero' => (string) $i,
                'tipo' => $tipo,
                'piso' => 1,
                'habilitado' => true,
            ]);
        }

        return back()->with('success', 'Bus registrado exitosamente y asientos generados.');
    }

    public function update(Request $request, Bus $bus)
    {
        if ($bus->cooperativa_id !== auth()->user()->cooperativa_id) abort(403);

        $data = $request->validate([
            'numero_disco' => 'required|string|max:50',
            'placa' => 'required|string|max:20|unique:buses,placa,' . $bus->id,
            'categoria_bus_id' => 'required|exists:categorias_bus,id',
            'habilitado' => 'boolean',
            'marca_chasis' => 'nullable|string|max:100',
            'carroceria' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($bus->foto_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($bus->foto_url);
            }
            $data['foto_url'] = $request->file('foto')->store('buses', 'public');
        }

        $data['habilitado'] = $request->has('habilitado');
        $bus->update($data);

        return back()->with('success', 'Bus actualizado exitosamente.');
    }

    public function destroy(Bus $bus)
    {
        if ($bus->cooperativa_id !== auth()->user()->cooperativa_id) abort(403);

        try {
            $bus->delete();
            return back()->with('success', 'Bus eliminado.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se puede eliminar el bus porque tiene hojas de ruta o historial asignado.');
        }
    }
}
