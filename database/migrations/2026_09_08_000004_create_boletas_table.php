<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->cascadeOnDelete();
            $table->smallInteger('periodo_mes');
            $table->smallInteger('periodo_anio');
            $table->text('ruta_pdf');
            $table->string('estado', 20)->default('pendiente'); // pendiente, enviada, error
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Constraint de unicidad por empleado y periodo
            $table->unique(['empleado_id', 'periodo_anio', 'periodo_mes'], 'uniq_boleta_emp_periodo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletas');
    }
};
