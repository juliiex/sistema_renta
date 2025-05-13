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
        Schema::create('reporte_problema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartamento_id')->constrained('apartamento')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuario')->onDelete('cascade');
            $table->text('descripcion');
            $table->string('tipo')->default('general'); // Mejorado: tipo de problema
            $table->string('estado')->default('pendiente'); // Estado predeterminado
            $table->timestamp('fecha_reporte')->useCurrent(); // Fecha automática
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_problema');
    }
};
