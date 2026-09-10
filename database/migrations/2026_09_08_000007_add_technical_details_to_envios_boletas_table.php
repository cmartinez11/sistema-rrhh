<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('envios_boletas', function (Blueprint $table) {
            $table->string('message_id')->nullable()->after('estado_envio');
            $table->jsonb('headers_raw')->nullable()->after('message_id');
            $table->text('cuerpo_html')->nullable()->after('headers_raw');
        });
    }

    public function down(): void
    {
        Schema::table('envios_boletas', function (Blueprint $table) {
            $table->dropColumn(['message_id', 'headers_raw', 'cuerpo_html']);
        });
    }
};
