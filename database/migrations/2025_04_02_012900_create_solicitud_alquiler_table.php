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
        Schema::create('solicitud_alquiler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuario')->onDelete('cascade');
            $table->foreignId('apartamento_id')->constrained('apartamento')->onDelete('cascade');
            $table->timestamp('fecha_solicitud')->useCurrent(); // Fecha automática
            $table->enum('estado_solicitud', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente'); // Estados definidos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_alquiler');
    }
};
