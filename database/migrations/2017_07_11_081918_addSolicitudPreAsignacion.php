<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSolicitudPreAsignacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_preasignacion', function (Blueprint $table){
            $table->increments('id');
            $table->integer('tipo_documento_identidad_id')->unsigned()->index();
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->integer('vehiculo_servicio_id')->unsigned()->index();
            $table->string('numero_documento_identidad');
            $table->string('numero_motor');
            $table->string('numero_chasis');
            $table->string('correo_electronico_solicitante');
            $table->string('numero_telefono_solicitante');
            $table->string('nombre_solicitante');
            $table->string('manifiesto_importacion');
            $table->string('observacion')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('tipo_documento_identidad_id')->references('id')->on('usuario_tipo_documento');
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
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
