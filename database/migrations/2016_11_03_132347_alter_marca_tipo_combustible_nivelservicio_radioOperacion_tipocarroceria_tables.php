<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMarcaTipoCombustibleNivelservicioRadioOperacionTipocarroceriaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marca_vehiculo', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('tipo_vehiculo', function (Blueprint $table) {
            $table->timestamps();
            $table->renameColumn('description', 'name');
        });

        Schema::table('clase_combustible', function (Blueprint $table) {
            $table->timestamps();
            $table->renameColumn('description', 'name');
        });

        Schema::table('tipo_carroceria', function (Blueprint $table) {
            $table->timestamps();
            $table->renameColumn('description', 'name');
        });

        Schema::table('nivel_servicio', function (Blueprint $table) {
            $table->timestamps();
            $table->renameColumn('description', 'name');
        });

        Schema::table('radio_operacion', function (Blueprint $table) {
            $table->timestamps();
            $table->renameColumn('description', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marca_vehiculo', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('tipo_vehiculo', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->renameColumn('name', 'description');
        });

        Schema::table('clase_combustible', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->renameColumn('name', 'description');
        });

        Schema::table('tipo_carroceria', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->renameColumn('name', 'description');
        });

        Schema::table('nivel_servicio', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->renameColumn('name', 'description');
        });

        Schema::table('radio_operacion', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->renameColumn('name', 'description');
        });
    }
}
