<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPqrRespuesta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('gd_pqr_respuesta');
        Schema::create('gd_pqr_respuesta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gd_pqr_id')->unsigned()->index();
            $table->foreign('gd_pqr_id')->references('id')->on('gd_pqr');
            $table->string('numero_oficio');
            $table->string('documento_respuesta');
            $table->string('anexos')->nullable();
            $table->string('numero_guia')->nullable();
            $table->date('fecha_envio')->nullable();
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

    }
}
