<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvioBoleta extends Model
{
    use HasFactory;

    protected $table = 'envios_boletas';

    protected $fillable = [
        'boleta_id',
        'fecha_envio',
        'estado_envio',
        'message_id',
        'headers_raw',
        'cuerpo_html',
        'mensaje_error',
        'enviado_por',
        'confirmado_at',
        'token_confirmacion',
        'ip_confirmacion',
        'user_agent_confirmacion',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'confirmado_at' => 'datetime',
        'headers_raw' => 'array',
    ];

    public function boleta(): BelongsTo
    {
        return $this->belongsTo(Boleta::class, 'boleta_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enviado_por');
    }
}
