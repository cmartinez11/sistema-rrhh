<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boletas', function (Blueprint $table) {
            // 1. Agregar columna tipo_periodo (primera_quincena, segunda_quincena)
            $table->string('tipo_periodo', 30)->default('segunda_quincena')->after('periodo_anio');

            // 2. Eliminar restricción de unicidad antigua
            $table->dropUnique('uniq_boleta_emp_periodo');

            // 3. Crear nueva restricción única compuesta incluyendo tipo_periodo
            $table->unique(['empleado_id', 'periodo_anio', 'periodo_mes', 'tipo_periodo'], 'boletas_emp_periodo_tipo_unique');
        });
    }

    public function down(): void
    {
        Schema::table('boletas', function (Blueprint $table) {
            $table->dropUnique('boletas_emp_periodo_tipo_unique');
            $table->unique(['empleado_id', 'periodo_anio', 'periodo_mes'], 'uniq_boleta_emp_periodo');
            $table->dropColumn('tipo_periodo');
        });
    }
};
