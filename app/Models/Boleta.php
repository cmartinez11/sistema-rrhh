<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Boleta extends Model
{
    use HasFactory;

    protected $table = 'boletas';

    protected $fillable = [
        'empleado_id',
        'periodo_mes',
        'periodo_anio',
        'tipo_periodo',
        'ruta_pdf',
        'estado',
        'created_by',
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
        return $this->hasMany(EnvioBoleta::class, 'boleta_id');
    }

    public function ultimoEnvio()
    {
        return $this->hasOne(EnvioBoleta::class, 'boleta_id')->latestOfMany('fecha_envio');
    }

    public function getNombreMesAttribute(): string
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return $meses[$this->periodo_mes] ?? 'Mes ' . $this->periodo_mes;
    }

    public function getNombreTipoPeriodoAttribute(): string
    {
        return ($this->tipo_periodo === 'primera_quincena') ? '1ra Quincena' : 'Fin de Mes';
    }

    public function getPeriodoFormateadoAttribute(): string
    {
        return "{$this->nombre_tipo_periodo} - {$this->nombre_mes} {$this->periodo_anio}";
    }

    public function getNombrePeriodoCompletoAttribute(): string
    {
        return "{$this->nombre_tipo_periodo} - {$this->nombre_mes} {$this->periodo_anio}";
    }

    public function existeArchivo(): bool
    {
        return Storage::disk('boletas')->exists($this->ruta_pdf);
    }
}
