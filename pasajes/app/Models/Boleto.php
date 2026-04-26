<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Boleto extends Model
{
    use HasFactory;

    protected $table = 'boletos';

    protected $fillable = [
        'hoja_ruta_id', 'asiento_id', 'user_id', 'vendido_por',
        'nombre_pasajero', 'cedula_pasajero', 'tipo_pasajero',
        'origen_abordaje', 'destino_desembarque',
        'precio', 'qr_code', 'estado', 'pdf_url', 'vendido_en_ruta',
    ];

    protected $casts = [
        'precio'          => 'decimal:2',
        'vendido_en_ruta' => 'boolean',
    ];

    public function hojaRuta(): BelongsTo
    {
        return $this->belongsTo(HojaRuta::class);
    }

    public function asiento(): BelongsTo
    {
        return $this->belongsTo(Asiento::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendido_por');
    }

    public function comprobante(): HasOne
    {
        return $this->hasOne(Comprobante::class);
    }

    public function reembolsoSolicitud(): HasOne
    {
        return $this->hasOne(ReembolsoSolicitud::class);
    }

    public function estaValidado(): bool
    {
        return $this->estado === 'Validado';
    }

    public function puedeAbordar(): bool
    {
        return $this->estado === 'Validado';
    }
}
