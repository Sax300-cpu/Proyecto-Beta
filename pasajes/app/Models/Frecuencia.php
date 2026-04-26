<?php

namespace App\Models;

use App\Models\Scopes\CooperativaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Frecuencia extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new CooperativaScope());
    }

    protected $table = 'frecuencias';

    protected $fillable = ['ruta_id', 'hora_salida', 'resolucion_ant', 'es_directa', 'activa'];

    protected $casts = [
        'activa'     => 'boolean',
        'es_directa' => 'boolean',
    ];

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class);
    }

    public function hojasRuta(): HasMany
    {
        return $this->hasMany(HojaRuta::class);
    }

    public function paradas()
    {
        return $this->belongsToMany(Parada::class, 'frecuencia_paradas')
                    ->withPivot('orden', 'tiempo_estimado_llegada', 'precio_desde_origen')
                    ->orderByPivot('orden');
    }

    /**
     * Scope: solo frecuencias activas y cuya ruta esté activa.
     */
    public function scopeHabilitadas($query)
    {
        return $query->where('activa', true)
                     ->whereHas('ruta', fn ($q) => $q->where('activa', true));
    }
}
