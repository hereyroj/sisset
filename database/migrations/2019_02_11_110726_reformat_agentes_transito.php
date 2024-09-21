<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatAgentesTransito extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comparendo_entidad', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('user_agente', function (Blueprint $table) {
            $table->integer('comparendo_entidad_id')->unsigned()->index();
            $table->foreign('comparendo_entidad_id')->references('id')->on('comparendo_entidad');
        });

        Schema::table('comparendo_vehiculo', function (Blueprint $table) {
            $table->dropForeign('comparendo_vehiculo_empresa_transportadora_id_foreign');
            $table->dropForeign('comparendo_vehiculo_vehiculo_radio_operacion_id_foreign');
            $table->string('propietario_nombre')->nullable()->change();
            $table->string('licencia_transito')->nullable()->change();
            $table->dropColumn('vehiculo_radio_operacion_id');
            $table->dropColumn('empresa_transportadora_id');
        });

        Schema::table('comparendo_vehiculo', function (Blueprint $table) {            
            $table->integer('vehiculo_radio_operacion_id')->index()->unsigned()->nullable();
            $table->integer('empresa_transportadora_id')->index()->unsigned()->nullable();
            $table->foreign('vehiculo_radio_operacion_id')->references('id')->on('vehiculo_radio_operacion');
            $table->foreign('empresa_transportadora_id')->references('id')->on('empresa_transporte');
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
