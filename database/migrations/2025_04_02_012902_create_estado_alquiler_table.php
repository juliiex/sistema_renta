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
        Schema::create('estado_alquiler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contrato')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuario')->onDelete('cascade');
            $table->string('estado_pago')->default('pendiente'); // pendiente, pagado, atrasado
            $table->date('fecha_reporte')->default(now()); // Fecha automática de reporte mensual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_alquiler');
    }
};
