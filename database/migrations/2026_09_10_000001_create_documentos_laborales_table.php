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
        Schema::create('documentos_laborales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->cascadeOnDelete();
            $table->string('tipo_documento'); // reglamento_interno, memorandum, no_renovacion, despido
            $table->date('fecha_emision');
            $table->string('asunto_motivo')->nullable();
            $table->string('ruta_pdf');
            $table->string('estado_envio')->default('pendiente'); // pendiente, enviado, error
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tipo_documento');
            $table->index('empleado_id');
            $table->index('fecha_emision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_laborales');
    }
};
