<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToAllTables extends Migration
{
    /**
     * Lista de tablas para añadir soft delete
     */
    protected $tables = [
        'apartamento',
        'contrato',
        'edificio',
        'estado_alquiler',
        'evaluacion',
        'permiso',
        'queja',
        'recordatorio_pago',
        'reporte_problema',
        'rol',
        'roles_permisos',
        'solicitud_alquiler',
        'usuario',
        'usuarios_roles'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
