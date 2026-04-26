<?php
$hojas = App\Models\HojaRuta::with(['frecuencia.ruta.cooperativa', 'bus.categoria', 'bus.asientos', 'boletos'])
    ->where('fecha', today()->format('Y-m-d'))
    ->whereIn('estado', ['Pendiente', 'En Ruta'])
    ->whereHas('frecuencia', function ($q) {
        $q->where('activa', true)
          ->whereHas('ruta', function ($r) {
              $r->where('activa', true)
                ->where('origen', 'ILIKE', "%Ambato%")
                ->where('destino', 'ILIKE', "%Quito%");
          });
    })->get();
echo "Count: " . $hojas->count() . "\n";
