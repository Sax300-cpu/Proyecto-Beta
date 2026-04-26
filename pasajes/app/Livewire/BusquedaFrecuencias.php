<?php

namespace App\Livewire;

use App\Models\Frecuencia;
use App\Models\HojaRuta;
use Livewire\Component;

class BusquedaFrecuencias extends Component
{
    public string $origen     = '';
    public string $destino    = '';
    public string $fecha      = '';
    public string $cooperativa = '';
    public string $tipo_viaje  = '';
    public string $tipo_asiento = '';

    public function mount(): void
    {
        $this->fecha = today()->format('Y-m-d');
    }

    public function render()
    {
        $hojas = collect();

        if ($this->origen && $this->destino && $this->fecha) {
            $hojas = HojaRuta::with([
                'frecuencia.ruta.cooperativa',
                'bus.categoria',
                'bus.asientos',
                'boletos',
            ])
            ->where('fecha', $this->fecha)
            ->whereIn('estado', ['Pendiente', 'En Ruta'])
            ->whereHas('frecuencia', function ($q) {
                $q->where('activa', true)
                  ->whereHas('ruta', function ($r) {
                      $r->where('activa', true)
                        ->where('origen', 'ILIKE', "%{$this->origen}%")
                        ->where('destino', 'ILIKE', "%{$this->destino}%");
                  });
            })
            ->when($this->cooperativa, fn ($q) =>
                $q->whereHas('frecuencia.ruta.cooperativa', fn ($c) =>
                    $c->where('nombre', 'ILIKE', "%{$this->cooperativa}%")
                )
            )
            ->when($this->tipo_viaje === 'directa', fn ($q) =>
                $q->whereHas('frecuencia', fn ($f) => $f->where('es_directa', true))
            )
            ->when($this->tipo_viaje === 'con_paradas', fn ($q) =>
                $q->whereHas('frecuencia', fn ($f) => $f->where('es_directa', false))
            )
            ->get()
            ->map(function (HojaRuta $hoja) {
                $ocupados = $hoja->boletos
                    ->whereIn('estado', ['Pendiente', 'Validado', 'Abordado'])
                    ->count();
                $hoja->asientos_disponibles = $hoja->bus->capacidad_asientos - $ocupados;
                return $hoja;
            });
        }

        return view('livewire.busqueda-frecuencias', ['hojas' => $hojas]);
    }
}
