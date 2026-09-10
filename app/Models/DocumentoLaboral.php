<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class DocumentoLaboral extends Model
{
    use HasFactory;

    protected $table = 'documentos_laborales';

    protected $fillable = [
        'empleado_id',
        'tipo_documento',
        'fecha_emision',
        'asunto_motivo',
        'ruta_pdf',
        'estado_envio',
        'created_by',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function envios(): HasMany
    {
        return $this->hasMany(EnvioDocumento::class, 'documento_id');
    }

    public function ultimoEnvio(): HasOne
    {
        return $this->hasOne(EnvioDocumento::class, 'documento_id')->latestOfMany();
    }

    public function existeArchivo(): bool
    {
        return !empty($this->ruta_pdf) && Storage::disk('boletas')->exists($this->ruta_pdf);
    }

    public function getTipoNombreAttribute(): string
    {
        return match ($this->tipo_documento) {
            'reglamento_interno' => 'Reglamento Interno (RIT)',
            'memorandum' => 'Memorándum',
            'no_renovacion' => 'Carta de No Renovación',
            'despido' => 'Carta de Despido',
            default => 'Documento Laboral',
        };
    }
}
