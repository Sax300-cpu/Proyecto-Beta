<?php

namespace App\Models;

use App\Models\Scopes\CooperativaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruta extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new CooperativaScope());
    }

    protected $table = 'rutas';

    protected $fillable = ['cooperativa_id', 'origen', 'destino', 'activa'];

    protected $casts = ['activa' => 'boolean'];

    public function cooperativa(): BelongsTo
    {
        return $this->belongsTo(Cooperativa::class);
    }

    public function frecuencias(): HasMany
    {
        return $this->hasMany(Frecuencia::class);
    }

    public function getDescripcionAttribute(): string
    {
        return "{$this->origen} → {$this->destino}";
    }
}
