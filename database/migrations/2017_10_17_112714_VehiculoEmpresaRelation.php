<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehiculoEmpresaRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo_empresa_transporte', function (Blueprint $table){
            $table->integer('vehiculo_id')->unsigned()->index();
            $table->integer('empresa_transporte_id')->unsigned()->index();
            $table->integer('nivel_servicio_id')->unsigned()->index();
            $table->integer('radio_operacion_id')->unsigned()->index();
            $table->string('zona_operacion');
            $table->string('numero_interno');
            $table->date('fecha_afiliacion');
            $table->date('fecha_retiro')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculo');
            $table->foreign('empresa_transporte_id')->references('id')->on('empresa_transporte');
            $table->foreign('nivel_servicio_id')->references('id')->on('vehiculo_nivel_servicio');
            $table->foreign('radio_operacion_id')->references('id')->on('vehiculo_radio_operacion');
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
