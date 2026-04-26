<?php

namespace App\Models;

use App\Models\Scopes\CooperativaScope;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected static function booted(): void
    {
        static::addGlobalScope(new CooperativaScope());
    }

    protected $fillable = [
        'name', 'email', 'password',
        'cooperativa_id', 'cedula', 'fecha_nacimiento', 'telefono',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'fecha_nacimiento'  => 'date',
        ];
    }

    public function cooperativa(): BelongsTo
    {
        return $this->belongsTo(Cooperativa::class);
    }

    public function boletos(): HasMany
    {
        return $this->hasMany(Boleto::class);
    }

    public function boletosVendidos(): HasMany
    {
        return $this->hasMany(Boleto::class, 'vendido_por');
    }

    public function hojasRuta(): HasMany
    {
        return $this->hasMany(HojaRuta::class, 'chofer_id');
    }

    /**
     * Regla de negocio: mayor de 13 años para comprar.
     */
    public function puedeComprar(): bool
    {
        if (! $this->fecha_nacimiento) {
            return false;
        }
        return $this->fecha_nacimiento->age >= 13;
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }
}
