<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cargos = [
            'Administrador',
            'Asistente Administrativo',
            'Asistente Contable',
            'Asistente de Almacén',
            'Asistente de Comex. Exterior',
            'Asistente de Facturación',
            'Asistente de Logística',
            'Asistente de Planeamiento',
            'Asistente de Planta',
            'Asistente de SSOMA',
            'Asistente de Ventas',
            'Asistente de Ventas y Cobranzas',
            'Auxiliar de Control de Calidad',
            'Ayudante de Despacho',
            'Conductor',
            'Encargado de TI',
            'Enfermera Ocupacional',
            'Gerente de Planta y Operaciones',
            'Gerente General',
            'Jefa de SIG',
            'Jefe de Contabilidad',
            'Jefe de Créditos y Cobranzas',
            'Jefe de Logística',
            'Jefe de Mantenimiento',
            'Jefe de Planta',
            'Operador de Montacarga',
            'Operario de Extrusión A/B',
            'Operario de Extrusión PP',
            'Operario de Impresión',
            'Operario de Laminado',
            'Operario de Limpieza',
            'Operario de Mezclado',
            'Operario de Montacarga',
            'Operario de Multisac',
            'Operario de Preforma',
            'Operario de Producción',
            'Operario de Rollomatic',
            'Operario de Sellado PP',
            'Operario de Tapas',
            'Operario de Termoformado',
            'Personal de Limpieza',
            'Practicante Contable',
            'Practicante de Calidad',
            'Recursos Humanos',
            'Secretaria de Gerencia General',
            'Sellador',
            'Supervisor de Planta',
            'Supervisor de SSOMA',
            'Técnico de Mantenimiento',
            'Técnico Electrónico',
            'Vigilante',
        ];

        foreach ($cargos as $nombre) {
            Cargo::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
