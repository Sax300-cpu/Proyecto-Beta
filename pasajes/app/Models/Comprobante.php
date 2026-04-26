<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobantes';

    protected $fillable = [
        'boleto_id', 'imagen_url', 'validado_por', 'estado', 'metodo_pago', 'referencia_pago', 'observaciones', 'metadata', 'validado_at',
    ];

    protected $casts = [
        'validado_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }

    public function validadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validado_por');
    }
}
