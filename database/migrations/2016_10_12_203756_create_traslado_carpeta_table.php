<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrasladoCarpetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traslado_carpeta', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->dateTime('fecha_traslado');
            $table->string('lugar_traslado');
            $table->string('autorizacion');
            $table->integer('carpeta_id')->unsigned()->unique();
            $table->foreign('carpeta_id')->references('id')->on('archivo_carpeta');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('traslado_carpeta');
    }
}
