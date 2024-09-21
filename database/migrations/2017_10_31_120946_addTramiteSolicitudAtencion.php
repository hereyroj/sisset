<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTramiteSolicitudAtencion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite_solicitud_atencion', function (Blueprint $table){
            $table->increments('id');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->integer('tramite_solicitud_turno_id')->unsigned()->index();
            $table->integer('ventanilla_id')->unsigned()->index();
            $table->string('observacion');
            $table->tinyInteger('terminacion');
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->foreign('tramite_solicitud_turno_id')->references('id')->on('tramite_solicitud_turno');
            $table->foreign('ventanilla_id')->references('id')->on('ventanilla');
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
