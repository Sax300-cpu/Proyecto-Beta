<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaBus extends Model
{
    use HasFactory;

    protected $table = 'categorias_bus';

    protected $fillable = ['nombre', 'descripcion', 'precio_base'];

    protected $casts = [
        'precio_base' => 'decimal:2',
    ];

    public function buses(): HasMany
    {
        return $this->hasMany(Bus::class, 'categoria_bus_id');
    }
}
