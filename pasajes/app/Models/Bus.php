<?php

namespace App\Models;

use App\Models\Scopes\CooperativaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new CooperativaScope());
    }

    protected $table = 'buses';

    protected $fillable = [
        'cooperativa_id', 'categoria_bus_id', 'numero_disco', 'placa',
        'chasis', 'carroceria', 'marca_chasis', 'foto_url', 'capacidad_asientos', 'habilitado',
    ];

    protected $casts = [
        'habilitado'        => 'boolean',
        'capacidad_asientos' => 'integer',
    ];

    public function cooperativa(): BelongsTo
    {
        return $this->belongsTo(Cooperativa::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaBus::class, 'categoria_bus_id');
    }

    public function asientos(): HasMany
    {
        return $this->hasMany(Asiento::class);
    }

    public function hojasRuta(): HasMany
    {
        return $this->hasMany(HojaRuta::class);
    }

    /**
     * ¿Está en ruta actualmente? (estado En Ruta en hoja del día de hoy)
     */
    public function estaEnRuta(): bool
    {
        return $this->hojasRuta()
            ->where('fecha', today())
            ->where('estado', 'En Ruta')
            ->exists();
    }

    /**
     * ¿El bus ya partió para una hoja de ruta dada?
     */
    public function yaPartio(HojaRuta $hojaRuta): bool
    {
        if ($hojaRuta->estado === 'En Ruta' || $hojaRuta->estado === 'Completada') {
            return true;
        }

        // Verificamos también por la hora de salida de la frecuencia
        if ($hojaRuta->hora_partida_real) {
            return true;
        }

        $horaSalida = $hojaRuta->frecuencia->hora_salida;
        return now()->format('H:i:s') > $horaSalida && $hojaRuta->fecha->isToday();
    }
}
