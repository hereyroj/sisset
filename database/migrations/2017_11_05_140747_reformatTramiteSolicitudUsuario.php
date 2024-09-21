<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatTramiteSolicitudUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_solicitud_usuario', function(Blueprint $table){
            $table->dropForeign(['tramite_solicitud_id']);
            $table->integer('tramite_solicitud_turno_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_turno_id')->references('id')->on('tramite_solicitud_turno');
            $table->dropColumn('tramite_solicitud_id');
        });

        Schema::table('tramite_solicitud_turno', function (Blueprint $table){
            $table->dropColumn('llamado');
            $table->dropColumn('vencido');
            $table->dropColumn('atendido');
            $table->smallInteger('preferente')->default(0)->change();
            $table->integer('funcionario_rellamado_id')->unsigned()->index()->nullable();
            $table->foreign('funcionario_rellamado_id')->references('id')->on('users');
            $table->integer('tramite_solicitud_origen_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_origen_id')->references('id')->on('tramite_solicitud_origen');
        });

        Schema::table('tramite_solicitud', function(Blueprint $table){
            $table->dropForeign(['tramite_solicitud_origen_id']);
            $table->dropColumn('tramite_solicitud_origen_id');
        });

        Schema::table('tramite_solicitud_atencion', function(Blueprint $table){
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
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
