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
        Schema::create('apartamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuario')->onDelete('set null'); // Propietario (opcional)
            $table->foreignId('edificio_id')->constrained('edificio')->onDelete('cascade');
            $table->string('numero_apartamento');
            $table->integer('piso');
            $table->decimal('precio', 10, 2);
            $table->decimal('tamaño', 10, 2);
            $table->string('estado')->default('disponible'); // Disponible, ocupado, mantenimiento
            $table->string('imagen')->nullable(); // Imagen del apartamento
            $table->text('descripcion')->nullable(); // Descripción del apartamento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartamento');
    }
};
