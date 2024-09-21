<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameInspeccionNotificacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('inspeccion_notificacion', 'inspeccion_sancion');
        Schema::rename('inspeccion_notificacion_tipo', 'inspeccion_sancion_tipo');

        Schema::table('inspeccion_sancion', function (Blueprint $table){
            $table->dropForeign('inspeccion_notificacion_inspeccion_notificacion_tipo_id_foreign');
            $table->renameColumn('inspeccion_notificacion_tipo_id', 'inspeccion_sancion_tipo_id');
        });

        Schema::table('inspeccion_sancion', function (Blueprint $table){
            $table->renameColumn('nombre_notificado', 'nombre_sancionado');
            $table->renameColumn('documento_notificacion', 'documento_sancion');
            $table->foreign('inspeccion_sancion_tipo_id')->references('id')->on('inspeccion_sancion_tipo');
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
