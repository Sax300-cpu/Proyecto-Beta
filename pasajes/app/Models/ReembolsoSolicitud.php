<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReembolsoSolicitud extends Model
{
    use HasFactory;

    protected $table = 'reembolso_solicitudes';

    protected $fillable = [
        'boleto_id',
        'user_id',
        'motivo',
        'estado',
        'observaciones',
    ];

    public function boleto(): BelongsTo
    {
        return $this->belongsTo(Boleto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
