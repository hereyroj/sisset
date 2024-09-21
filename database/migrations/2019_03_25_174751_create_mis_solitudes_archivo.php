<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMisSolitudesArchivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_solicitud_motivo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('archivo_solicitud_funcionario', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->integer('archivo_sol_mo_id')->unsigned()->index();
            $table->foreign('archivo_sol_mo_id')->references('id')->on('archivo_solicitud_motivo');
            $table->string('placa');
            $table->integer('vehiculo_servicio_id')->unsigned()->index();
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->timestamps();
        });

        Schema::table('archivo_solicitud', function(Blueprint $table){
            $table->dropForeign('archivo_solicitud_tramite_servicio_id_foreign');
            $table->dropColumn('tramite_servicio_id');
            $table->morphs('origen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mis_solitudes_archivo');
    }
}
