<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoCompletoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapeo = [
            'Gerencia' => [
                'Administrador',
                'Gerente General',
                'Secretaria de Gerencia General',
                'Vigilante',
            ],
            'Planta' => [
                'Gerente de Planta y Operaciones',
                'Jefe de Planta',
                'Supervisor de Planta',
                'Asistente de Planta',
                'Asistente de Planeamiento',
                'Operario de Producción',
            ],
            'TI' => [
                'Encargado de TI',
            ],
            'Contabilidad' => [
                'Jefe de Contabilidad',
                'Asistente Contable',
                'Asistente de Facturación',
                'Practicante Contable',
            ],
            'Ventas' => [
                'Asistente de Ventas',
                'Asistente de Ventas y Cobranzas',
                'Jefe de Créditos y Cobranzas',
            ],
            'Comercio Exterior' => [
                'Asistente de Comex. Exterior',
            ],
            'RRHH' => [
                'Recursos Humanos',
                'Asistente Administrativo',
            ],
            'Calidad' => [
                'Jefa de SIG',
                'Auxiliar de Control de Calidad',
                'Practicante de Calidad',
            ],
            'SSOMA' => [
                'Supervisor de SSOMA',
                'Asistente de SSOMA',
            ],
            'Enfermería' => [
                'Enfermera Ocupacional',
            ],
            'Almacén' => [
                'Jefe de Logística',
                'Asistente de Logística',
                'Asistente de Almacén',
                'Operador de Montacarga',
                'Operario de Montacarga',
            ],
            'Despacho' => [
                'Ayudante de Despacho',
                'Conductor',
            ],
            'Mantenimiento' => [
                'Jefe de Mantenimiento',
                'Técnico de Mantenimiento',
                'Técnico Electrónico',
            ],
            'Limpieza' => [
                'Personal de Limpieza',
                'Operario de Limpieza',
            ],
            'Extrusión A/B' => [
                'Operario de Extrusión A/B',
            ],
            'Extrusión PP' => [
                'Operario de Extrusión PP',
            ],
            'Impresión' => [
                'Operario de Impresión',
            ],
            'Laminado' => [
                'Operario de Laminado',
            ],
            'Mezclado' => [
                'Operario Mezclador',
            ],
            'Multisac' => [
                'Operario de Multisac',
            ],
            'Preforma' => [
                'Operario de Preforma',
            ],
            'Rollomatic' => [
                'Operario Rollomatic',
            ],
            'Sellado PP' => [
                'Operario de Sellado PP',
                'Sellador',
            ],
            'Tapas' => [
                'Operario de Tapas',
            ],
            'Termoformado' => [
                'Operario de Termoformado',
            ],
        ];

        foreach ($mapeo as $nombreArea => $cargos) {
            $area = Area::firstOrCreate(
                ['nombre' => $nombreArea],
                ['descripcion' => "Área de {$nombreArea} de PLASTICOS FENIX"]
            );

            foreach ($cargos as $nombreCargo) {
                Cargo::firstOrCreate(
                    [
                        'nombre' => $nombreCargo,
                        'area_id' => $area->id,
                    ]
                );
            }
        }
    }
}
