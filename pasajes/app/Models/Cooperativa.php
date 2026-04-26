<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cooperativa extends Model
{
    use HasFactory;

    protected $table = 'cooperativas';

    protected $fillable = [
        'nombre', 'ruc', 'logo_url', 'direccion', 'telefono', 'email',
        'color_primario', 'color_secundario', 'facebook_url', 'instagram_url',
        'whatsapp', 'email_soporte', 'cuenta_bancaria', 'banco', 'titular_cuenta', 'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class);
    }

    public function rutas(): HasMany
    {
        return $this->hasMany(Ruta::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
