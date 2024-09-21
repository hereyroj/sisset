<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchivoSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_solicitud', function (Blueprint $table) {
            /*
             * Campos básicos de esta tabla
             */
            $table->increments('id')->unique();
            $table->dateTime('request_date');
            $table->dateTime('request_aproved')->nullable();
            $table->dateTime('folder_delivered')->nullable();
            $table->dateTime('folder_returned')->nullable();
            $table->integer('folder_id')->unsigned();
            $table->foreign('folder_id')->references('id')->on('archivo_carpeta');
            $table->integer('user_request_id')->unsigned();
            $table->foreign('user_request_id')->references('id')->on('users');
            $table->integer('tramite_id')->unsigned();
            $table->foreign('tramite_id')->references('id')->on('tramite');
            $table->timestamps();
            $table->softDeletes();
            /*
             * Campos opcionales de acuerdo a quien solicita la carpeta
             * 1 - Usuario quien recibe la carpeta: este campo es para solamente los funcionarios de tramites ya que el que siempre solicitara la carpeta para esa dependencia será el usuario con el
             * rol de DigiTurno
             */
            $table->integer('user_delivered_id')->unsigned()->nullable();
            $table->foreign('user_delivered_id')->references('id')->on('users');
            /*
             * 2 - digiturno_code: este campo aplica unicamente cuando un usuario con el rol digiturno pide una carpeta
             */
            $table->string('digiturno_code')->nullable();
            /*
             * 3 - Estado: es para definir si se realizó el trámite, quedo pendiente o se devolvio la carpeta sin hacerle cambios. Observacion: este se usa en caso del que el encargadod el WEBSERVICES encuentre alguna falencia
             */
            $table->string('status')->nullable();
            $table->string('status_observation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('archivo_solicitud');
    }
}
