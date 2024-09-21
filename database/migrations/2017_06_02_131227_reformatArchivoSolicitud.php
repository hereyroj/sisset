<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatArchivoSolicitud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('archivo_cancelacion_motivo', 'archivo_carpeta_ca_mo');
        Schema::rename('archivo_denegacion', 'archivo_solicitud_de_mo');
        Schema::rename('archivo_traslado_carpeta', 'archivo_carpeta_traslado');
        Schema::rename('archivo_validacion', 'archivo_solicitud_va_ve');

        Schema::table('archivo_solicitud_denegacion', function (Blueprint $table) {
            $table->increments('id');
            $table->renameColumn('archivo_denegacion_id', 'archivo_solicitud_de_mo_id');
        });

        Schema::table('archivo_solicitud_validacion', function (Blueprint $table) {
            $table->increments('id');
            $table->renameColumn('archivo_validacion_id', 'archivo_solicitud_va_ve_id');
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
