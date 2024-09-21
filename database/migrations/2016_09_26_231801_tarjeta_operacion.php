<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TarjetaOperacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarjeta_operacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sede');
            $table->string('numero_interno');
            $table->string('numero_motor');
            $table->date('fecha_vencimiento');
            $table->string('placa');
            $table->string('modelo');
            $table->string('zona_operacion');
            $table->string('capacidad_pasajeros');
            $table->string('capacidad_toneladas');
            $table->string('enable');
            $table->timestamps();
            $table->integer('tipo_vehiculo_id')->unsigned();
            $table->foreign('tipo_vehiculo_id')->references('id')->on('tipo_vehiculo');
            $table->integer('tipo_carroceria_id')->unsigned();
            $table->foreign('tipo_carroceria_id')->references('id')->on('tipo_carroceria');
            $table->integer('nivel_servicio_id')->unsigned();
            $table->foreign('nivel_servicio_id')->references('id')->on('nivel_servicio');
            $table->integer('marca_vehiculo_id')->unsigned();
            $table->foreign('marca_vehiculo_id')->references('id')->on('marca_vehiculo');
            $table->integer('radio_operacion_id')->unsigned();
            $table->foreign('radio_operacion_id')->references('id')->on('radio_operacion');
            $table->integer('empresa_transporte_id')->unsigned();
            $table->foreign('empresa_transporte_id')->references('id')->on('empresas_transporte');
            $table->integer('clase_combustible_id')->unsigned();
            $table->foreign('clase_combustible_id')->references('id')->on('clase_combustible');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tarjeta_operacion');
    }
}
