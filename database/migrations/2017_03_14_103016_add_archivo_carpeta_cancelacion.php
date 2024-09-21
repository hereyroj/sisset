<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArchivoCarpetaCancelacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_carpeta_cancelacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('archivo_carpeta_id')->unsigned();
            $table->foreign('archivo_carpeta_id')->references('id')->on('archivo_carpeta');
            $table->string('nro_certificado_runt');
            $table->date('fecha_cancelacion');
            $table->string('nro_acto_administrativo');
            $table->string('nombre_funcionario_autoriza');
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
        Schema::drop('archivo_carpeta_cancelacion');
    }
}
