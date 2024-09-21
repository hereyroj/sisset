<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexTramitesDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('tramite_solicitud_has_estado', function (Blueprint $table){
           $table->dropPrimary();
        });*/

        Schema::table('ventanilla_funcionario', function (Blueprint $table){
            $table->dropPrimary();
        });

        Schema::table('tramite_solicitud_asignacion', function (Blueprint $table){
            $table->dropPrimary();
        });

        Schema::table('archivo_solicitud_validacion', function (Blueprint $table){
            $table->dropPrimary();
        });

        Schema::table('archivo_solicitud_denegacion', function (Blueprint $table){
            $table->dropPrimary();
        });

        Schema::table('ventanilla_tramite', function (Blueprint $table){
            $table->dropPrimary();
        });

        Schema::table('sustrato_anulacion', function (Blueprint $table){
            $table->dropPrimary();
        });

        Schema::table('solicitud_preasignacion_rechazo', function (Blueprint $table){
            $table->dropPrimary();
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
