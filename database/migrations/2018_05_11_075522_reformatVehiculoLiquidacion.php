<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatVehiculoLiquidacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculo_liquidacion_descuento', function (Blueprint $table){
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
        });

        Schema::create('vehiculo_vig_des', function (Blueprint $table){
            $table->integer('vehiculo_liq_vig_id')->unsigned()->index();
            $table->foreign('vehiculo_liq_vig_id')->references('id')->on('vehiculo_liquidacion_vigencia');
            $table->integer('vehiculo_liq_des_id')->unsigned()->index();
            $table->foreign('vehiculo_liq_des_id')->references('id')->on('vehiculo_liquidacion_descuento');
        });

        Schema::create('vehiculo_liq_des', function (Blueprint $table){
            $table->integer('vehiculo_liq_id')->unsigned()->index();
            $table->foreign('vehiculo_liq_id')->references('id')->on('vehiculo_liquidacion');
            $table->integer('vehiculo_liq_des_id')->unsigned()->index();
            $table->foreign('vehiculo_liq_des_id')->references('id')->on('vehiculo_liquidacion_descuento');
        });

        Schema::table('vehiculo_liquidacion', function (Blueprint $table){
            $table->integer('vehiculo_liq_vig_id')->unsigned()->index();
            $table->foreign('vehiculo_liq_vig_id')->references('id')->on('vehiculo_liquidacion_vigencia');
        });

        Schema::table('vehiculo_liquidacion_vigencia', function (Blueprint $table){
            $table->renameColumn('meses_intereses', 'cantidad_meses_intereses');
        });

        Schema::drop('vehiculo_liquidacion_has_descuento');

        Schema::rename('vehiculo_base_gravable', 'vehiculo_liq_base_gravable');
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
