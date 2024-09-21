<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatEVtoPlaca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('especie_venal', 'placa');
        Schema::rename('especie_venal_preasignacion', 'placa_preasignacion');

        Schema::table('placa_preasignacion', function (Blueprint $table){
            $table->dropForeign('especie_venal_preasignacion_especie_venal_id_foreign');
            $table->dropForeign('especie_venal_preasignacion_solicitud_preasignacion_id_foreign');
            $table->renameColumn('especie_venal_id', 'placa_id');
            $table->foreign('placa_id')->references('id')->on('placa');
            $table->foreign('solicitud_preasignacion_id')->references('id')->on('solicitud_preasignacion');
        });

        Schema::table('tramite', function (Blueprint $table){
            $table->renameColumn('require_especie_venal', 'requiere_placa');
            $table->dropColumn('ignora_restriccion');
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
