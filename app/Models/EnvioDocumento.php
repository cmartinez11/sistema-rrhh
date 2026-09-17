<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvioDocumento extends Model
{
    use HasFactory;

    protected $table = 'envios_documentos';

    protected $fillable = [
        'documento_id',
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
        'headers_raw' => 'array',
        'fecha_envio' => 'datetime',
        'confirmado_at' => 'datetime',
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(DocumentoLaboral::class, 'documento_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enviado_por');
    }
}
