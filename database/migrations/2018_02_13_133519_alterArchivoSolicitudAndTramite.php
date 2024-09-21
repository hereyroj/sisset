<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArchivoSolicitudAndTramite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_solicitud', function (Blueprint $table){
            $table->dropForeign(['tramite_solicitud_id']);
            $table->dropColumn('tramite_solicitud_id');
            $table->integer('tramite_solicitud_turno_id')->unsigned()->index()->nullable();
            $table->foreign('tramite_solicitud_turno_id')->references('id')->on('tramite_solicitud_turno');
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
