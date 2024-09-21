<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatArchivoSolicitudDenegacionValidacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_solicitud_denegacion', function (Blueprint $table){
            $table->dropForeign(['archivo_solicitud_id']);
            $table->dropColumn('id');
            $table->primary(['archivo_solicitud_id', 'archivo_solicitud_de_mo_id'], 'archivo_solicitud_denegacion_id');
        });

        Schema::table('archivo_solicitud_validacion', function (Blueprint $table){
            $table->dropColumn('id');
            $table->primary(['archivo_solicitud_id', 'archivo_solicitud_va_ve_id'], 'archivo_solicitud_validacion_id');
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
