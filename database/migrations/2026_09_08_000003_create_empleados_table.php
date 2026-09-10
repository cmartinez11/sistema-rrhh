<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('dni', 20)->unique()->index();
            $table->foreignId('cargo_id')->constrained('cargos');
            $table->foreignId('area_id')->constrained('areas');
            $table->date('fecha_ingreso');
            $table->string('estado', 20)->default('activo');
            $table->string('email', 255)->unique();
            $table->string('telefono', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
