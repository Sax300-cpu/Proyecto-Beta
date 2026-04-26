<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asiento extends Model
{
    use HasFactory;

    protected $table = 'asientos';

    protected $fillable = ['bus_id', 'numero', 'tipo', 'piso', 'habilitado'];

    protected $casts = [
        'habilitado' => 'boolean',
        'piso'       => 'integer',
    ];

    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    /**
     * Verifica si este asiento está ocupado en una hoja de ruta dada.
     */
    public function estaOcupado(int $hojaRutaId): bool
    {
        return $this->boletos()
            ->where('hoja_ruta_id', $hojaRutaId)
            ->whereIn('estado', ['Pendiente', 'Validado', 'Abordado'])
            ->exists();
    }
}
