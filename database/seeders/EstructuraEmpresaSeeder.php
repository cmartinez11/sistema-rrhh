<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Cargo;
use Illuminate\Database\Seeder;

class EstructuraEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estructuras = [
            'Almacén' => ['Encargado de Almacén', 'Auxiliar de Almacén'],
            'Calidad' => ['Jefe de Calidad', 'Inspector de Calidad'],
            'Comercio Exterior' => ['Analista de Comercio Exterior', 'Asistente de Comercio Exterior'],
            'Contabilidad' => ['Contador General', 'Asistente Contable'],
            'Despacho' => ['Supervisor de Despacho', 'Auxiliar de Despacho'],
            'Enfermería' => ['Enfermero(a) Ocupacional'],
            'Extrusión A/B' => ['Supervisor de Extrusión A/B', 'Operario de Extrusión A/B'],
            'Extrusión PP' => ['Supervisor de Extrusión PP', 'Operario de Extrusión PP'],
            'Gerencia' => ['Gerente General', 'Asistente de Gerencia'],
            'Impresión' => ['Supervisor de Impresión', 'Operario Impresor'],
            'Laminado' => ['Supervisor de Laminado', 'Operario de Laminado'],
            'Limpieza' => ['Personal de Limpieza y Servicios Generales'],
            'Mantenimiento' => ['Jefe de Mantenimiento', 'Técnico de Mantenimiento'],
            'Marketing' => ['Especialista de Marketing'],
            'Mezclado' => ['Operario Mezclador'],
            'Molido' => ['Operario de Molino'],
            'Multisac' => ['Supervisor de Multisac', 'Operario de Multisac'],
            'Planta' => ['Jefe de Planta', 'Supervisor de Planta'],
            'Preforma' => ['Supervisor de Preforma', 'Operario de Preforma'],
            'Rollomatic' => ['Operario Rollomatic'],
            'RRHH' => ['Jefe de Recursos Humanos', 'Asistente de Recursos Humanos'],
            'Sellado PP' => ['Supervisor de Sellado PP', 'Operario de Sellado PP'],
            'SSOMA' => ['Ingeniero de SSOMA', 'Asistente de SSOMA'],
            'Tapas' => ['Supervisor de Producción de Tapas', 'Operario de Tapas'],
            'Termoformado' => ['Supervisor de Termoformado', 'Operario de Termoformado'],
            'TI' => ['Jefe de TI', 'Soporte Técnico de TI'],
        ];

        foreach ($estructuras as $nombreArea => $cargos) {
            $area = Area::firstOrCreate(
                ['nombre' => $nombreArea],
                ['descripcion' => "Área de {$nombreArea} de PLASTICOS FENIX"]
            );

            foreach ($cargos as $nombreCargo) {
                Cargo::firstOrCreate([
                    'nombre' => $nombreCargo,
                    'area_id' => $area->id,
                ]);
            }
        }
    }
}
