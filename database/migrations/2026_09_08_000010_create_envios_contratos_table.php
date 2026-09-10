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
        Schema::create('envios_contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->cascadeOnDelete();
            $table->timestampTz('fecha_envio');
            $table->string('estado_envio'); // exito, fallido
            $table->string('message_id')->nullable();
            $table->jsonb('headers_raw')->nullable();
            $table->text('cuerpo_html')->nullable();
            $table->text('mensaje_error')->nullable();
            $table->foreignId('enviado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios_contratos');
    }
};
