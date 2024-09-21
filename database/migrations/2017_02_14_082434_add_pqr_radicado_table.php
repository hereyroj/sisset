<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPqrRadicadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqr_radicado', function (Blueprint $table) {
            $table->increments('id');
            $table->string('primer_nombre');
            $table->string('segundo_nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->string('numero_documento');
            $table->string('direccion');
            $table->string('numero_telefono');
            $table->string('correo_electronico');
            $table->string('correo_electronico_notificacion');
            $table->string('asunto');
            $table->string('anexo_pdf');
            $table->string('anexo_jpeg');
            $table->string('anexo_png');
            $table->integer('tipo_oficio_id')->unsigned();
            $table->foreign('tipo_oficio_id')->references('id')->on('pqr_tipo_oficio');
            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')->references('id')->on('departamento');
            $table->integer('municipio_id')->unsigned();
            $table->foreign('municipio_id')->references('id')->on('municipio');
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
        Schema::drop('pqr_radicado');
    }
}
