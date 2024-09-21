<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEstadoCarpetaAddEstadoCarpetaColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carpeta_estado', function (Blueprint $table) {
            $table->string('estado_carpeta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carpeta_estado', function (Blueprint $table) {
            $table->dropColumn('estado_carpeta');
        });
    }
}
