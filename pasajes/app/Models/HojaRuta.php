<?php

namespace App\Models;

use App\Models\Scopes\CooperativaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HojaRuta extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new CooperativaScope());
    }

    protected $table = 'hojas_ruta';

    protected $fillable = [
        'frecuencia_id', 'bus_id', 'chofer_id', 'fecha', 'estado', 'hora_partida_real',
    ];

    protected $casts = [
        'fecha'             => 'date',
        'hora_partida_real' => 'datetime',
    ];

    public function frecuencia(): BelongsTo
    {
        return $this->belongsTo(Frecuencia::class);
    }

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function chofer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chofer_id');
    }

    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    /**
     * Regla de negocio: ¿El bus ya partió?
     * Oficinista NO puede vender si esto retorna true.
     * Chofer SÍ puede vender siempre.
     */
    public function yaPartio(): bool
    {
        if (in_array($this->estado, ['En Ruta', 'Completada'])) {
            return true;
        }
        if ($this->hora_partida_real !== null) {
            return true;
        }
        // Verificación por hora de salida de la frecuencia
        $horaSalida = $this->frecuencia->hora_salida;
        return $this->fecha->isToday() && now()->format('H:i:s') > $horaSalida;
    }

    /**
     * Asientos disponibles para esta hoja de ruta.
     */
    public function asientosDisponibles()
    {
        $asientosOcupados = $this->boletos()
            ->whereIn('estado', ['Pendiente', 'Validado', 'Abordado'])
            ->pluck('asiento_id');

        return $this->bus->asientos()
            ->where('habilitado', true)
            ->whereNotIn('id', $asientosOcupados)
            ->get();
    }
}
