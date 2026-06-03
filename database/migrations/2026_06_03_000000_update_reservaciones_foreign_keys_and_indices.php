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
        Schema::table('reservaciones', function (Blueprint $table) {
            // Eliminar restricciones en cascada anteriores
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['habitacion_id']);

            // Re-crear con restricción estricta
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('restrict');
            $table->foreign('habitacion_id')->references('id')->on('habitaciones')->onDelete('restrict');

            // Añadir índice compuesto para optimización de rangos de fechas
            $table->index(['habitacion_id', 'estado', 'fecha_entrada', 'fecha_salida']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservaciones', function (Blueprint $table) {
            $table->dropIndex(['habitacion_id', 'estado', 'fecha_entrada', 'fecha_salida']);
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['habitacion_id']);

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('habitacion_id')->references('id')->on('habitaciones')->onDelete('cascade');
        });
    }
};
