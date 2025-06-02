<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('rol', function (Blueprint $table) {
        $table->string('guard_name')->default('web');
    });

    Schema::table('permiso', function (Blueprint $table) {
        $table->string('guard_name')->default('web');
    });
}

public function down()
{
    Schema::table('rol', function (Blueprint $table) {
        $table->dropColumn('guard_name');
    });

    Schema::table('permiso', function (Blueprint $table) {
        $table->dropColumn('guard_name');
    });
}
};
