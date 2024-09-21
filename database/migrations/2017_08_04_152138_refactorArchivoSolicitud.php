<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorArchivoSolicitud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite_solicitud_finalizacion', function (Blueprint $table){
            $table->increments('id');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->integer('especie_venal_id')->unsigned()->index()->nullable();
            $table->integer('sustrato_id')->unsigned()->index()->nullable();
            $table->string('observacion')->nullable();
            $table->timestamps();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->foreign('especie_venal_id')->references('id')->on('especie_venal');
            $table->foreign('sustrato_id')->references('id')->on('sustrato');
        });

        Schema::table('archivo_carpeta_prestamo', function (Blueprint $table){
            $table->dropForeign(['funcionario_recibe_id']);
            $table->dropForeign(['funcionario_entrega_id']);
        });

        Schema::table('archivo_carpeta_prestamo', function (Blueprint $table){
            $table->integer('funcionario_recibe_id')->nullable()->unsigned()->change();
            $table->integer('funcionario_entrega_id')->nullable()->unsigned()->change();
            $table->dateTime('fecha_entrega')->nullable()->change();
            $table->foreign('funcionario_recibe_id')->references('id')->on('users');
            $table->foreign('funcionario_entrega_id')->references('id')->on('users');
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
