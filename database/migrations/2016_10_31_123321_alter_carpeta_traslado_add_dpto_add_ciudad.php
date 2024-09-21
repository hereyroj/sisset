<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarpetaTrasladoAddDptoAddCiudad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traslado_carpeta', function (Blueprint $table) {
            $table->dropColumn('lugar_traslado');
            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')->references('id')->on('departamento');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('id')->on('municipio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traslado_carpeta', function (Blueprint $table) {
            $table->string('lugar_traslado');
            $table->dropColumn('departamento_id');
            $table->dropColumn('municipio_id');
        });
    }
}
