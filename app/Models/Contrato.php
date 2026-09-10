<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'empleado_id',
        'fecha_ingreso',
        'fecha_inicio',
        'fecha_fin',
        'ruta_pdf',
        'estado',
        'estado_envio',
        'created_by',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
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
        return $this->hasMany(EnvioContrato::class, 'contrato_id');
    }

    public function ultimoEnvio(): HasOne
    {
        return $this->hasOne(EnvioContrato::class, 'contrato_id')->latestOfMany();
    }

    /**
     * Días restantes para el vencimiento del contrato.
     * Negativo si ya venció.
     */
    public function getDiasRestantesAttribute(): int
    {
        if (!$this->fecha_fin) {
            return 0;
        }

        $today = now()->startOfDay();
        $fechaFin = Carbon::parse($this->fecha_fin)->startOfDay();

        return (int) $today->diffInDays($fechaFin, false);
    }

    /**
     * Estado de alerta de vencimiento (semáforo).
     * - 'vencido': fecha_fin menor a hoy
     * - 'por_vencer': entre 0 y 30 días restantes
     * - 'vigente': más de 30 días restantes
     */
    public function getAlertaVencimientoAttribute(): string
    {
        if (!$this->fecha_fin) {
            return 'vigente';
        }

        $today = now()->startOfDay();
        $fechaFin = Carbon::parse($this->fecha_fin)->startOfDay();

        if ($fechaFin->isPast() && !$fechaFin->isToday()) {
            return 'vencido';
        }

        $dias = $this->dias_restantes;

        if ($dias <= 30) {
            return 'por_vencer';
        }

        return 'vigente';
    }

    /**
     * Clases CSS Tailwind para el Badge de Alerta.
     */
    public function getAlertaBadgeClassAttribute(): string
    {
        return match ($this->alerta_vencimiento) {
            'vencido' => 'bg-red-100 text-red-800 border border-red-300 dark:bg-red-950 dark:text-red-300',
            'por_vencer' => 'bg-amber-100 text-amber-800 border border-amber-300 dark:bg-amber-950 dark:text-amber-300',
            'vigente' => 'bg-emerald-100 text-emerald-800 border border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300',
            default => 'bg-gray-100 text-gray-800 border border-gray-300',
        };
    }

    /**
     * Etiqueta legible para el Badge de Alerta.
     */
    public function getAlertaLabelAttribute(): string
    {
        $dias = $this->dias_restantes;

        return match ($this->alerta_vencimiento) {
            'vencido' => 'Vencido (' . abs($dias) . ' d. vencido)',
            'por_vencer' => $dias === 0 ? 'Vence Hoy' : 'Por Vencer (' . $dias . ' d. restantes)',
            'vigente' => 'Vigente (' . $dias . ' d. restantes)',
            default => 'Desconocido',
        };
    }

    /**
     * Verificar si existe el archivo PDF en el disco de contratos/boletas.
     */
    public function existeArchivo(): bool
    {
        return !empty($this->ruta_pdf) && Storage::disk('boletas')->exists($this->ruta_pdf);
    }
}
