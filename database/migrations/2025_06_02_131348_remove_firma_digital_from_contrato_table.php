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
            // Eliminamos el campo firma_digital ya que ahora solo usaremos firma_imagen
            $table->dropColumn('firma_digital');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrato', function (Blueprint $table) {
            // Si necesitamos revertir, añadimos de nuevo el campo
            $table->string('firma_digital')->after('fecha_fin')->nullable();
        });
    }
};
