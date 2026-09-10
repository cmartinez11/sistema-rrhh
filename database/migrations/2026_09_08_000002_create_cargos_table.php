<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->foreignId('area_id')->constrained('areas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
