<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    use HasFactory;

    protected $table = 'paradas';

    protected $fillable = ['nombre', 'ciudad', 'provincia'];

    public function frecuencias()
    {
        return $this->belongsToMany(Frecuencia::class, 'frecuencia_paradas')
                    ->withPivot('orden', 'tiempo_estimado_llegada', 'precio_desde_origen')
                    ->orderByPivot('orden');
    }
}
