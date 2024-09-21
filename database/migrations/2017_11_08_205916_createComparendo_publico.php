<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComparendoPublico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_agente', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('placa');
            $table->date('fecha_ingreso');
            $table->date('fecha_retiro')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('comparendo_tipo', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('comparendo_infraccion', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('descripcion');
            $table->integer('comparendo_tipo_id')->unsigned()->index();
            $table->foreign('comparendo_tipo_id')->references('id')->on('comparendo_tipo');
            $table->timestamps();
        });

        Schema::create('comparendo', function(Blueprint $table){
            $table->increments('id');
            $table->string('numero')->unique();
            $table->float('valor');
            $table->string('opcionInmovilizacion');
            $table->string('observacionInmovilizacion');
            $table->longText('observacion');
            $table->dateTime('fecha_realizacion');
            $table->integer('comparendo_infraccion_id')->unsigned()->index();
            $table->foreign('comparendo_infraccion_id')->references('id')->on('comparendo_infraccion');
            $table->integer('comparendo_tipo_id')->unsigned()->index();
            $table->foreign('comparendo_tipo_id')->references('id')->on('comparendo_tipo');
            $table->integer('agente_id')->unsigned()->index();
            $table->foreign('agente_id')->references('id')->on('user_agente');
            $table->string('comparendo');
            $table->timestamps();
        });

        Schema::create('comparendo_infractor', function(Blueprint $table){
            $table->increments('id');
            $table->string('nombre');
            $table->string('telefono');
            $table->string('direccion');
            $table->string('licencia_numero');
            $table->date('licencia_fecha_vencimiento');
            $table->string('numero_documento');
            $table->integer('tipo_documento_id')->unsigned()->index();
            $table->foreign('tipo_documento_id')->references('id')->on('usuario_tipo_documento');
            $table->integer('comparendo_id')->unsigned()->index();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->timestamps();
        });

        Schema::create('comparendo_vehiculo', function(Blueprint $table){
            $table->increments('id');
            $table->string('placa', 6);
            $table->string('licencia_transito');
            $table->string('propietario_nombre');
            $table->integer('vehiculo_servicio_id')->unsigned()->index();
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->string('tarjeta_operacion');
            $table->integer('vehiculo_radio_operacion_id')->unsigned()->index();
            $table->integer('empresa_transportadora_id')->unsigned()->index();
            $table->integer('comparendo_id')->unsigned()->index();
            $table->integer('placa_ciudad_expedicion_id')->unsigned()->index();
            $table->integer('placa_dpto_expedicion_id')->unsigned()->index();
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->foreign('vehiculo_radio_operacion_id')->references('id')->on('vehiculo_radio_operacion');
            $table->foreign('empresa_transportadora_id')->references('id')->on('empresa_transporte');
            $table->foreign('placa_ciudad_expedicion_id')->references('id')->on('municipio');
            $table->foreign('placa_dpto_expedicion_id')->references('id')->on('departamento');
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->timestamps();
        });

        Schema::create('comparendo_pago_modalidad', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unsigued();
            $table->timestamps();
        });

        Schema::create('comparendo_pago', function (Blueprint $table){
            $table->increments('id');
            $table->tinyInteger('cuotas');
            $table->integer('valor_cuota');
            $table->tinyInteger('descuento')->nullable();
            $table->integer('comparendo_pago_modalidad_id')->unsigned()->index();
            $table->foreign('comparendo_pago_modalidad_id')->references('id')->on('comparendo_pago_modalidad');
            $table->integer('comparendo_id')->unsigned()->index();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->timestamps();
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
