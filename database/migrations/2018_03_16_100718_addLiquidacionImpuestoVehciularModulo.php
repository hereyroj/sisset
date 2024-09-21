<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLiquidacionImpuestoVehciularModulo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo_linea', function (Blueprint $table){
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->string('cilindraje');
            $table->timestamps();
            $table->integer('vehiculo_marca_id')->unsigned()->index();
            $table->foreign('vehiculo_marca_id')->references('id')->on('vehiculo_marca');
        });

        Schema::table('vehiculo', function (Blueprint $table){
            $table->integer('vehiculo_linea_id')->nullable()->unsigned()->index();
            $table->foreign('vehiculo_linea_id')->references('id')->on('vehiculo_linea');
        });

        Schema::create('vehiculo_base_gravable', function (Blueprint $table){
            $table->increments('id');
            $table->integer('vehiculo_linea_id')->unsigned()->index();
            $table->foreign('vehiculo_linea_id')->references('id')->on('vehiculo_linea');
            $table->string('modelo', 4);
            $table->string('vigencia', 4);
            $table->float('avaluo');
            $table->timestamps();
        });

        Schema::create('vehiculo_liquidacion', function (Blueprint $table){
            $table->increments('id');
            $table->float('valor_total');
            $table->float('valor_mora_total')->nullable();
            $table->float('valor_descuento_total')->nullable();
            $table->date('fecha_vencimiento');
            $table->timestamps();
            $table->integer('vehiculo_id')->nullable()->unsigned()->index();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculo');
        });

        Schema::create('vehiculo_liquidacion_descuento', function (Blueprint $table){
            $table->increments('id');
            $table->string('concepto')->unique();
            $table->float('porcentaje');
            $table->timestamps();
        });

        Schema::create('vehiculo_liquidacion_has_descuento', function (Blueprint $table){
            $table->integer('ve_li_descuento_id')->unsigned()->index();
            $table->foreign('ve_li_descuento_id')->references('id')->on('vehiculo_liquidacion_descuento');
            $table->integer('ve_li_id')->unsigned()->index();
            $table->foreign('ve_li_id')->references('id')->on('vehiculo_liquidacion');
        });

        Schema::create('vehiculo_liquidacion_vigencia', function (Blueprint $table){
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->float('porcentaje_motocicleta');
            $table->float('porcentaje_automovil');
            $table->float('porcentaje_carga');
            $table->float('porcentaje_pasajeros');
            $table->integer('meses_intereses')->unsigned()->default(1);
            $table->timestamps();
        });

        Schema::create('vehiculo_liquidacion_mes', function (Blueprint $table){
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        Schema::create('vehiculo_liquidacion_vigencia_has_mes', function (Blueprint $table){
            $table->integer('ve_li_vi_id')->nullable()->unsigned()->index();
            $table->foreign('ve_li_vi_id')->references('id')->on('vehiculo_liquidacion_vigencia');
            $table->integer('ve_li_mes_id')->nullable()->unsigned()->index();
            $table->foreign('ve_li_mes_id')->references('id')->on('vehiculo_liquidacion_mes');
            $table->float('porcentaje_interes');
        });

        Schema::create('vehiculo_propietario', function (Blueprint $table){
            $table->increments('id');
            $table->string('nombre');
            $table->string('numero_documento');
            $table->integer('tipo_documento_id')->unsigned()->index();
            $table->foreign('tipo_documento_id')->references('id')->on('usuario_tipo_documento');
            $table->string('telefono');
            $table->integer('departamento_id')->unsigned()->index();
            $table->foreign('departamento_id')->references('id')->on('departamento');
            $table->integer('municipio_id')->unsigned()->index();
            $table->foreign('municipio_id')->references('id')->on('municipio');
            $table->string('direccion');
            $table->timestamps();
        });

        Schema::create('vehiculo_has_propietario', function (Blueprint $table){
            $table->integer('vehiculo_id')->nullable()->unsigned()->index();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculo');
            $table->integer('vehiculo_propietario_id')->nullable()->unsigned()->index();
            $table->foreign('vehiculo_propietario_id')->references('id')->on('vehiculo_propietario');
            $table->integer('estado');
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
