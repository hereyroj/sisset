<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTramiteServicioAndRefactoringTramiteSolicitud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Renombramiento de tablas
         */
        Schema::rename('tramite_solicitud_has_estado', 'tramite_servicio_has_estado');

        Schema::rename('tramite_solicitud_finalizacion', 'tramite_servicio_finalizacion');

        Schema::rename('tramite_solicitud_recibo', 'tramite_servicio_recibo');

        Schema::rename('tramite_solicitud_estado', 'tramite_servicio_estado');

        /*
         * Creacion de tramite servicio
         */
        Schema::create('tramite_servicio', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->integer('vehiculo_servicio_id')->unsigned()->index();
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->string('placa', 6);
            $table->timestamps();         
        });

        /*
         * Eliminacion de llaves foraneas a tramite_solicitud
         */  
        Schema::table('tramite_servicio_has_estado', function (Blueprint $table) {
            $table->dropForeign('tramite_solicitud_has_estado_tramite_solicitud_id_foreign');
            $table->dropForeign('tramite_solicitud_has_estado_tramite_solicitud_estado_id_foreign');
        });

        Schema::table('tramite_servicio_finalizacion', function (Blueprint $table) {
            $table->dropForeign('tramite_solicitud_finalizacion_tramite_solicitud_id_foreign');
        });

        Schema::table('tramite_servicio_recibo', function (Blueprint $table) {
            $table->dropForeign('tramite_solicitud_recibo_tramite_solicitud_id_foreign');
        });

        /*
         * Nueva vinculacion a tramite_servicio
         */
        Schema::table('tramite_servicio_has_estado', function (Blueprint $table) {
            $table->dropColumn('tramite_solicitud_id');
            $table->dropColumn('tramite_solicitud_estado_id');            
            $table->integer('tramite_servicio_id')->unsigned()->index();
            $table->foreign('tramite_servicio_id')->references('id')->on('tramite_servicio');
            $table->integer('tramite_servicio_estado_id')->unsigned()->index();
            $table->foreign('tramite_servicio_estado_id')->references('id')->on('tramite_servicio_estado');
        });

        Schema::table('tramite_servicio_finalizacion', function (Blueprint $table) {
            $table->dropColumn('tramite_solicitud_id');
            $table->integer('tramite_servicio_id')->unsigned()->index();
            $table->foreign('tramite_servicio_id')->references('id')->on('tramite_servicio');
        });

        Schema::table('tramite_servicio_recibo', function (Blueprint $table) {
            $table->dropColumn(['tramite_solicitud_id']);
            $table->integer('tramite_servicio_id')->unsigned()->index();
            $table->foreign('tramite_servicio_id')->references('id')->on('tramite_servicio');
        });

        /*
         * Creacion de tramite servicio has tramite
         */
        Schema::create('tramite_servicio_has_tramite', function (Blueprint $table) {
            $table->integer('tramite_servicio_id')->unsigned()->index();
            $table->foreign('tramite_servicio_id')->references('id')->on('tramite_servicio');
            $table->integer('tramite_id')->unsigned()->index();
            $table->foreign('tramite_id')->references('id')->on('tramite');
        });

        Schema::table('tramite_servicio', function (Blueprint $table) {
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
        });

        Schema::table('archivo_solicitud', function (Blueprint $table) {
            $table->dropForeign('archivo_solicitud_tramite_solicitud_turno_id_foreign');
        });

        Schema::table('archivo_solicitud', function (Blueprint $table) {
            $table->dropColumn('tramite_solicitud_turno_id');
            $table->integer('tramite_servicio_id')->unsigned()->index();
            $table->foreign('tramite_servicio_id')->references('id')->on('tramite_servicio');
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
