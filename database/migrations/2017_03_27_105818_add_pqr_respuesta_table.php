<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPqrRespuestaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqr_respuesta', function (Blueprint $table) {
            $table->integer('pqr_asignacion_id')->unsigned();
            $table->foreign('pqr_asignacion_id')->references('id')->on('pqr_asignacion');
            $table->integer('pqr_radicado_id')->unsigned()->index();
            $table->foreign('pqr_radicado_id')->references('id')->on('pqr_radicado');
            $table->string('numero_oficio');
            $table->string('documento_respuesta');
            $table->string('anexos')->nullable();
            $table->string('numero_guia')->nullable();
            $table->string('estado_envio')->nullable();
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
        Schema::drop('pqr_respuesta');
    }
}
