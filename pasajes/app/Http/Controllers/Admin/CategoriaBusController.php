<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaBus;
use Illuminate\Http\Request;

class CategoriaBusController extends Controller
{
    public function index()
    {
        $categorias = CategoriaBus::paginate(10);
        return view("admin.categorias-bus.index", compact("categorias"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required|string|max:255|unique:categorias_bus,nombre",
            "descripcion" => "nullable|string",
            "precio_base" => "required|numeric|min:0",
        ]);

        CategoriaBus::create($request->all());

        return back()->with("success", "Categoría de bus creada exitosamente.");
    }

    public function update(Request $request, CategoriaBus $categoriaBus)
    {
        $request->validate([
            "nombre" => "required|string|max:255|unique:categorias_bus,nombre," . $categoriaBus->id,
            "descripcion" => "nullable|string",
            "precio_base" => "required|numeric|min:0",
        ]);

        $categoriaBus->update($request->all());

        return back()->with("success", "Categoría de bus actualizada exitosamente.");
    }

    public function destroy(CategoriaBus $categoriaBus)
    {
        try {
            $categoriaBus->delete();
            return back()->with("success", "Categoría de bus eliminada exitosamente.");
        } catch (\Exception $e) {
            return back()->with("error", "No se puede eliminar la categoría porque tiene buses asignados.");
        }
    }
}