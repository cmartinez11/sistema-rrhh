<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('envios_boletas', function (Blueprint $table) {
            $table->string('token_confirmacion')->nullable()->unique();
            $table->string('ip_confirmacion')->nullable();
            $table->text('user_agent_confirmacion')->nullable();
        });

        Schema::table('envios_contratos', function (Blueprint $table) {
            $table->string('token_confirmacion')->nullable()->unique();
            $table->string('ip_confirmacion')->nullable();
            $table->text('user_agent_confirmacion')->nullable();
        });

        Schema::table('envios_documentos', function (Blueprint $table) {
            $table->string('token_confirmacion')->nullable()->unique();
            $table->string('ip_confirmacion')->nullable();
            $table->text('user_agent_confirmacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('envios_boletas', function (Blueprint $table) {
            $table->dropColumn(['token_confirmacion', 'ip_confirmacion', 'user_agent_confirmacion']);
        });

        Schema::table('envios_contratos', function (Blueprint $table) {
            $table->dropColumn(['token_confirmacion', 'ip_confirmacion', 'user_agent_confirmacion']);
        });

        Schema::table('envios_documentos', function (Blueprint $table) {
            $table->dropColumn(['token_confirmacion', 'ip_confirmacion', 'user_agent_confirmacion']);
        });
    }
};
