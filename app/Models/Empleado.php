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
        'estado',
        'email',
        'telefono',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombres} {$this->apellidos}";
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
