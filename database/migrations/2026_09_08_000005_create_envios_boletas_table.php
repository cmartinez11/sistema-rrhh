<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('envios_boletas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boleta_id')->constrained('boletas')->cascadeOnDelete();
            $table->timestampTz('fecha_envio')->useCurrent(); // Postgres TIMESTAMP WITH TIME ZONE
            $table->string('estado_envio', 20); // exito, fallido
            $table->text('mensaje_error')->nullable();
            $table->foreignId('enviado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('envios_boletas');
    }
};
