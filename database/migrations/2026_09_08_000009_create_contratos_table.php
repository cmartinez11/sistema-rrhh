<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->cascadeOnDelete();
            $table->date('fecha_ingreso');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('ruta_pdf');
            $table->string('estado')->default('activo'); // activo, vencido, renovado
            $table->string('estado_envio')->default('pendiente'); // pendiente, enviado, error
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('fecha_fin');
            $table->index('empleado_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
