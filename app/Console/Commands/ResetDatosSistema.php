<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ResetDatosSistema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rrhh:reset-data {--force : Forzar la ejecución sin solicitar confirmación interactiva}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetear toda la data transaccional y estructural de prueba del sistema de RRHH conservando los usuarios y roles.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->warn('====================================================');
        $this->warn('  RESET DE DATA TRANSACCIONAL Y ESTRUCTURAL - RRHH  ');
        $this->warn('====================================================');

        if (!$this->option('force')) {
            if (!$this->confirm('¿Está seguro de eliminar TODA la data de prueba (áreas, cargos, empleados, boletas, contratos y auditorías)? Esta acción no se puede deshacer.')) {
                $this->info('Operación cancelada por el usuario.');
                return Command::SUCCESS;
            }
        }

        try {
            $this->info('1. Vaciando tablas en PostgreSQL (RESTART IDENTITY CASCADE)...');

            DB::statement('TRUNCATE TABLE envios_contratos, envios_boletas, contratos, boletas, empleados, cargos, areas RESTART IDENTITY CASCADE;');

            $this->info('✓ Tablas vaciadas y contadores de ID reiniciados a 1.');

            $this->info('2. Vaciando archivos físicos del disco privado (boletas)...');

            Storage::disk('boletas')->deleteDirectory('boletas');
            Storage::disk('boletas')->deleteDirectory('contratos');
            
            // Recrear directorios limpios
            Storage::disk('boletas')->makeDirectory('boletas');
            Storage::disk('boletas')->makeDirectory('contratos');

            $this->info('✓ Archivos PDF de boletas y contratos eliminados correctamente.');

            $this->newLine();
            $this->info('====================================================');
            $this->info('  RESET COMPLETADO CON ÉXITO                         ');
            $this->info('  Las cuentas de usuario y roles permanecen intactos.');
            $this->info('====================================================');

            return Command::SUCCESS;

        } catch (Throwable $e) {
            $this->error('Error al ejecutar el reset de datos: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
