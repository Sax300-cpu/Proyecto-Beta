<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parada;
use Illuminate\Http\Request;

class ParadaController extends Controller
{
    public function index()
    {
        $paradas = Parada::paginate(15);
        return view("admin.paradas.index", compact("paradas"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required|string|max:255",
            "ciudad" => "required|string|max:255",
            "provincia" => "nullable|string|max:255",
        ]);

        Parada::create($request->all());

        return back()->with("success", "Parada creada exitosamente.");
    }

    public function update(Request $request, Parada $parada)
    {
        $request->validate([
            "nombre" => "required|string|max:255",
            "ciudad" => "required|string|max:255",
            "provincia" => "nullable|string|max:255",
        ]);

        $parada->update($request->all());

        return back()->with("success", "Parada actualizada exitosamente.");
    }

    public function destroy(Parada $parada)
    {
        try {
            $parada->delete();
            return back()->with("success", "Parada eliminada.");
        } catch (\Exception $e) {
            return back()->with("error", "No se puede eliminar la parada porque está asociada a una frecuencia.");
        }
    }
}