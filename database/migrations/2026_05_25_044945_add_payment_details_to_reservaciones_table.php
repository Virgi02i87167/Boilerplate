<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservaciones', function (Blueprint $table) {
            $table->string('tipo_documento')->default('ticket')->after('metodo_pago');
            $table->string('razon_social')->nullable()->after('tipo_documento');
            $table->string('nrc')->nullable()->after('razon_social');
            $table->string('nit_dui')->nullable()->after('nrc');
            $table->string('giro')->nullable()->after('nit_dui');
            $table->string('numero_referencia')->nullable()->after('giro');
            $table->string('banco_destino')->nullable()->after('numero_referencia');
            $table->string('numero_comprobante')->nullable()->after('banco_destino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservaciones', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_documento',
                'razon_social',
                'nrc',
                'nit_dui',
                'giro',
                'numero_referencia',
                'banco_destino',
                'numero_comprobante',
            ]);
        });
    }
};
