<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatComparendosAndAddMandamientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comparendo', function (Blueprint $table) {
            $table->renameColumn('observacion', 'observacion_agente');
            $table->renameColumn('comparendo', 'documento');    
        });

        Schema::create('licencia_categoria', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();    
            $table->timestamps();
        });

        Schema::create('comparendo_infractor_tipo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();    
            $table->timestamps();
        });

        Schema::table('comparendo_infractor', function (Blueprint $table) {
            $table->string('direccion_runt')->nullable();
            $table->string('telefono_runt')->nullable();
            $table->integer('licencia_categoria_id')->unsigned()->index()->nullable();
            $table->foreign('licencia_categoria_id')->references('id')->on('licencia_categoria');            
            $table->integer('ciudad_id')->unsigned()->index()->nullable();
            $table->foreign('ciudad_id')->references('id')->on('municipio');
            $table->integer('ciudad_runt_id')->unsigned()->index()->nullable();
            $table->foreign('ciudad_runt_id')->references('id')->on('municipio');
            $table->string('direccion_electronica')->nullable();
            $table->integer('infractor_tipo_id')->unsigned()->index();
            $table->foreign('infractor_tipo_id')->references('id')->on('comparendo_infractor_tipo');
        });

        Schema::create('comparendo_testigo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');  
            $table->string('numero_documento');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->integer('tipo_documento_id')->unsigned()->index();
            $table->foreign('tipo_documento_id')->references('id')->on('usuario_tipo_documento');
            $table->integer('comparendo_id')->unsigned()->index();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->timestamps();            
        });

        Schema::table('comparendo_vehiculo', function (Blueprint $table) {
            $table->dropForeign('comparendo_vehiculo_placa_ciudad_expedicion_id_foreign');
            $table->dropForeign('comparendo_vehiculo_placa_dpto_expedicion_id_foreign');
            $table->dropColumn('placa_ciudad_expedicion_id');
            $table->dropColumn('placa_dpto_expedicion_id');
            $table->string('licencia_transito_otto')->nullable();
            $table->integer('vehiculo_nivel_servicio_id')->unsigned()->index()->nullable();
            $table->foreign('vehiculo_nivel_servicio_id')->references('id')->on('vehiculo_nivel_servicio');
            $table->integer('prop_tipo_documento_id')->unsigned()->index()->nullable();
            $table->foreign('prop_tipo_documento_id')->references('id')->on('usuario_tipo_documento');
            $table->string('prop_numero_documento')->nullable();
        });

        Schema::table('comparendo_inmovilizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('observacion')->nullable()->change();
            $table->string('patio_nombre');
            $table->string('patio_direccion');
            $table->string('grua_numero');
            $table->string('grua_placa');
            $table->string('consecutivo')->unique();
        });

        Schema::table('comparendo_infraccion', function (Blueprint $table) {
            $table->boolean('inmoviliza');
        });

        Schema::create('via_tipo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('comparendo_ubicacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comparendo_id')->unsigned()->index();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->integer('via_principal_tipo_id')->unsigned()->index();
            $table->foreign('via_principal_tipo_id')->references('id')->on('via_tipo');
            $table->integer('via_secundaria_tipo_id')->unsigned()->index();
            $table->foreign('via_secundaria_tipo_id')->references('id')->on('via_tipo');
            $table->string('via_principal_nombre');
            $table->string('via_secundaria_nombre');
            $table->string('barrio_vereda');
            $table->integer('ciudad_id')->unsigned()->index()->nullable();
            $table->foreign('ciudad_id')->references('id')->on('municipio');
            $table->timestamps();
        });

        Schema::create('mandamiento_pago', function (Blueprint $table) {
            $table->increments('id');
            $table->string('consecutivo')->unique();
            $table->string('documento')->unique()->nullable();
            $table->string('valor');
            $table->date('fecha_mandamiento');
            $table->string('consecutivo_sancion')->unique();
            $table->string('fecha_sancion');
            $table->string('documento_sancion')->nullable();
            $table->integer('comparendo_id')->unsigned()->index()->nullable();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->timestamps();
        });

        Schema::create('ma_finalizacion_tipo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('mandamiento_finalizacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ma_finalizacion_tipo_id')->unsigned()->index();
            $table->foreign('ma_finalizacion_tipo_id')->references('id')->on('ma_finalizacion_tipo');
            $table->integer('mandamiento_pago_id')->unsigned()->index();
            $table->foreign('mandamiento_pago_id')->references('id')->on('mandamiento_pago');
            $table->date('fecha_finalizacion');
            $table->text('observacion')->nullable();
            $table->string('documento')->nullable();
            $table->timestamps();
        });        

        Schema::create('ma_notificacion_tipo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->tinyInteger('dia_cantidad');
            $table->char('dia_tipo', 1);
            $table->tinyInteger('orden')->unique();
            $table->timestamps();
        });

        Schema::create('mandamiento_notificacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ma_notificacion_tipo_id')->unsigned()->index();
            $table->foreign('ma_notificacion_tipo_id')->references('id')->on('ma_notificacion_tipo');
            $table->integer('mandamiento_pago_id')->unsigned()->index();
            $table->foreign('mandamiento_pago_id')->references('id')->on('mandamiento_pago');
            $table->string('consecutivo')->unique();
            $table->string('documento')->unique()->nullable();
            $table->date('fecha_notificacion');
            $table->date('fecha_max_presentacion');
            $table->timestamps();
        });

        Schema::create('ma_notificacion_entrega', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_entrega');
            $table->string('observacion');
            $table->integer('mandamiento_notificacion_id')->unsigned()->index();
            $table->foreign('mandamiento_notificacion_id')->references('id')->on('mandamiento_notificacion');
            $table->timestamps();
        });

        Schema::create('ma_devolucion_motivo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('ma_notificacion_devolucion', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_devolucion');
            $table->string('observacion');
            $table->integer('mandamiento_notificacion_id')->unsigned()->index();
            $table->foreign('mandamiento_notificacion_id')->references('id')->on('mandamiento_notificacion');
            $table->integer('ma_devolucion_motivo_id')->unsigned()->index();
            $table->foreign('ma_devolucion_motivo_id')->references('id')->on('ma_devolucion_motivo');
            $table->timestamps();
        });

        Schema::create('mandamiento_medio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->boolean('require_guia');
            $table->timestamps();
        });

        Schema::create('ma_notificacion_medio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_guia')->nullable();
            $table->integer('empresa_transporte_id')->unsigned()->index()->nullable();
            $table->foreign('empresa_transporte_id')->references('id')->on('empresa_transporte');
            $table->integer('mandamiento_notificacion_id')->unsigned()->index();
            $table->foreign('mandamiento_notificacion_id')->references('id')->on('mandamiento_notificacion');
            $table->integer('mandamiento_medio_id')->unsigned()->index();
            $table->foreign('mandamiento_medio_id')->references('id')->on('mandamiento_medio');
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
