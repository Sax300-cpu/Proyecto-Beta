<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Ruta;
use App\Models\Parada;

class RutaParadas extends Component
{
    public $ruta;
    public $paradasDisponibles;
    public $paradasSeleccionadas = [];
    public $nuevaParadaId;

    public function mount(Ruta $ruta)
    {
        $this->ruta = $ruta->load(\"paradas\");
        $this->paradasSeleccionadas = $this->ruta->paradas->map(function ($parada) {
            return [
                \"id\" => $parada->id,
                \"nombre\" => $parada->nombre,
                \"orden\" => $parada->pivot->orden,
                \"tiempo_estimado_llegada\" => $parada->pivot->tiempo_estimado_llegada,
                \"precio_desde_origen\" => $parada->pivot->precio_desde_origen,
            ];
        })->toArray();

        $this->loadParadasDisponibles();
    }

    public function loadParadasDisponibles()
    {
        $existingParadaIds = collect($this->paradasSeleccionadas)->pluck(\"id\");
        $this->paradasDisponibles = Parada::whereNotIn(\"id\", $existingParadaIds)->get();
    }

    public function addParada()
    {
        $this->validate([\"nuevaParadaId\" => \"required|exists:paradas,id\"]);

        $parada = Parada::find($this->nuevaParadaId);
        if ($parada && !collect($this->paradasSeleccionadas)->contains(\"id\", $parada->id)) {
            $this->paradasSeleccionadas[] = [
                \"id\" => $parada->id,
                \"nombre\" => $parada->nombre,
                \"orden\" => count($this->paradasSeleccionadas) + 1,
                \"tiempo_estimado_llegada\" => null,
                \"precio_desde_origen\" => 0,
            ];
            $this->nuevaParadaId = null;
            $this->loadParadasDisponibles();
        }
    }

    public function removeParada(int $paradaId)
    {
        $this->paradasSeleccionadas = array_values(array_filter($this->paradasSeleccionadas, fn($p) => $p[\"id\"] !== $paradaId));
        $this->updateParadaOrders();
        $this->loadParadasDisponibles();
    }

    public function updateParadaOrders()
    {
        foreach ($this->paradasSeleccionadas as $index => &$parada) {
            $parada[\"orden\"] = $index + 1;
        }
    }

    public function saveParadas()
    {
        $this->validate([
            \"paradasSeleccionadas.*.orden\" => \"required|integer|min:1\\