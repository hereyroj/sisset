<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatPqrRespuesta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('gd_pqr_respuesta');

        Schema::drop('gd_pqr_saliente');

        Schema::table('gd_pqr', function (Blueprint $table){
            $table->integer('gd_pqr_respuesta_id')->unsigned()->index()->nullable();
            $table->string('radicados_respuesta')->nullable();
            $table->foreign('gd_pqr_respuesta_id')->references('id')->on('gd_pqr');
        });

        Schema::create('empresa_mensajeria', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('gd_pqr_modalidad_envio', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('gd_pqr_envio', function (Blueprint $table){
            $table->increments('id');
            $table->integer('gd_pqr_id')->unsigned()->index();
            $table->integer('empresa_mensajeria_id')->unsigned()->index()->nullable();
            $table->integer('gd_pqr_modalidad_envio_id')->unsigned()->index();
            $table->dateTime('fecha_hora_envio')->nullable();
            $table->dateTime('fecha_hora_entrega')->nullable();
            $table->string('documento_entregado')->nullable();
            $table->string('numero_guia')->nullable();
            $table->foreign('gd_pqr_id')->references('id')->on('gd_pqr');
            $table->foreign('empresa_mensajeria_id')->references('id')->on('empresa_mensajeria');
            $table->foreign('gd_pqr_modalidad_envio_id')->references('id')->on('gd_pqr_modalidad_envio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
