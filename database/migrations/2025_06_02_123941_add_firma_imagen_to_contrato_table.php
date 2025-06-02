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
        Schema::table('contrato', function (Blueprint $table) {
            // Agregar un nuevo campo para la imagen de la firma
            $table->string('firma_imagen')->nullable()->after('firma_digital');
            // Agregar un campo para el estado de la firma
            $table->string('estado_firma')->default('pendiente')->after('firma_imagen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrato', function (Blueprint $table) {
            $table->dropColumn('firma_imagen');
            $table->dropColumn('estado_firma');
        });
    }
};
