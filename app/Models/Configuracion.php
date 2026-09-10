<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];

    protected $casts = [
        'valor' => 'array',
    ];

    public static function obtener(string $clave, $default = null)
    {
        $config = static::where('clave', $clave)->first();
        if (!$config) {
            return $default;
        }

        return $config->valor;
    }

    public static function establecer(string $clave, $valor, ?string $descripcion = null): self
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'descripcion' => $descripcion ?? "Configuración para {$clave}"
            ]
        );
    }
}
