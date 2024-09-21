<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('clase_combustible','vehiculo_combustible');
        Schema::rename('marca_vehiculo','vehiculo_marca');
        Schema::rename('nivel_servicio','vehiculo_nivel_servicio');
        Schema::rename('radio_operacion','vehiculo_radio_operacion');
        Schema::rename('tipo_carroceria','vehiculo_carroceria');
        Schema::rename('tipo_vehiculo','vehiculo_clase');
        Schema::rename('empresas_transporte','empresa_transporte');
        Schema::rename('traslado_carpeta','archivo_traslado_carpeta');
        Schema::rename('carpeta_estado','archivo_carpeta_estado');
        Schema::rename('validacion_solicitud','archivo_solicitud_validacion');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('vehiculo_combustible','clase_combustible');
        Schema::rename('vehiculo_marca','marca_vehiculo');
        Schema::rename('vehiculo_nivel_servicio','nivel_servicio');
        Schema::rename('vehiculo_radio_operacion','radio_operacion');
        Schema::rename('vehiculo_carroceria','tipo_carroceria');
        Schema::rename('vehiculo_clase','tipo_vehiculo');
        Schema::rename('empresa_transporte','empresas_transporte');
        Schema::rename('archivo_traslado_carpeta','traslado_carpeta');
        Schema::rename('archivo_carpeta_estado','carpeta_estado');
        Schema::rename('archivo_solicitud_validacion','validacion_solicitud');
    }
}
