<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcuerdosDePago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acuerdo_pago', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_acuerdo')->unique();
            $table->string('valor_total');
            $table->string('pago_inicial');
            $table->string('acuerdo')->nullable();
            $table->tinyInteger('cuotas');
            $table->boolean('incumplido')->default(false);
            $table->boolean('cancelado')->default(false);
            $table->boolean('anulado')->default(false);
            $table->boolean('vigente')->default(true);
            $table->date('fecha_acuerdo');
            $table->timestamps();
        });

        Schema::create('acuerdo_pago_comparendo', function (Blueprint $table) {
            $table->integer('comparendo_id')->unsigned()->index();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->integer('acuerdo_pago_id')->unsigned()->index();
            $table->foreign('acuerdo_pago_id')->references('id')->on('acuerdo_pago'); 
        });

        Schema::create('acuerdo_pago_cuota', function (Blueprint $table) {
            $table->increments('id');
            $table->string('valor');
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->string('consignacion_factura')->nullable();
            $table->string('factura_sintrat')->nullable();
            $table->boolean('vencida')->default(false);
            $table->boolean('pagada')->default(false);
            $table->boolean('pendiente')->default(true);
            $table->integer('acuerdo_pago_id')->unsigned()->index();
            $table->foreign('acuerdo_pago_id')->references('id')->on('acuerdo_pago');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('acuerdo_pago_deudor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('tipo_documento_id')->unsigned()->index();
            $table->foreign('tipo_documento_id')->references('id')->on('usuario_tipo_documento');
            $table->string('numero_documento');
            $table->string('telefono');
            $table->string('correo_electronico')->nullable();
            $table->string('direccion');
            $table->integer('acuerdo_pago_id')->unsigned()->index();
            $table->foreign('acuerdo_pago_id')->references('id')->on('acuerdo_pago');
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
