<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'nombres',
        'apellidos',
        'dni',
        'cargo_id',
        'area_id',
        'fecha_ingreso',
        'fecha_inicio_contrato',
        'fecha_fin_contrato',
        'estado',
        'email',
        'telefono',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_inicio_contrato' => 'date',
        'fecha_fin_contrato' => 'date',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function getAlertaContratoAttribute(): array
    {
        if (!$this->fecha_fin_contrato) {
            return [
                'estado' => 'sin_contrato',
                'label' => 'Sin fecha fin',
                'badge' => 'bg-slate-100 text-slate-700',
            ];
        }

        $diffDays = (int) now()->startOfDay()->diffInDays($this->fecha_fin_contrato->startOfDay(), false);

        if ($diffDays < 0) {
            return [
                'estado' => 'vencido',
                'label' => 'Vencido (' . abs($diffDays) . ' d)',
                'badge' => 'bg-red-100 text-red-700 border-red-200',
            ];
        }

        if ($diffDays <= 30) {
            return [
                'estado' => 'por_vencer',
                'label' => 'Por vencer (' . $diffDays . ' d)',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
            ];
        }

        return [
            'estado' => 'vigente',
            'label' => 'Vigente (' . $diffDays . ' d)',
            'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        ];
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function boletas(): HasMany
    {
        return $this->hasMany(Boleta::class, 'empleado_id');
    }
}
