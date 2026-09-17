<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->date('fecha_inicio_contrato')->nullable();
            $table->date('fecha_fin_contrato')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropIndex(['fecha_fin_contrato']);
            $table->dropColumn(['fecha_inicio_contrato', 'fecha_fin_contrato']);
        });
    }
};
