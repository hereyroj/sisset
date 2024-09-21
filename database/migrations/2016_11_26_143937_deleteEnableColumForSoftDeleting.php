<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteEnableColumForSoftDeleting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresa_transporte', function (Blueprint $table) {
            $table->dropColumn('enable');
        });

        Schema::table('tarjeta_operacion', function (Blueprint $table) {
            $table->dropColumn('enable');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('enable');
            $table->string('lock_session');
        });

        Schema::table('vehiculo_carroceria', function (Blueprint $table) {
            $table->dropColumn('enable');
        });

        Schema::table('vehiculo_clase', function (Blueprint $table) {
            $table->dropColumn('enable');
        });

        Schema::table('vehiculo_combustible', function (Blueprint $table) {
            $table->dropColumn('enable');
        });

        Schema::table('vehiculo_marca', function (Blueprint $table) {
            $table->dropColumn('enable');
        });

        Schema::table('vehiculo_nivel_servicio', function (Blueprint $table) {
            $table->dropColumn('enable');
        });

        Schema::table('vehiculo_radio_operacion', function (Blueprint $table) {
            $table->dropColumn('enable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresa_transporte', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });

        Schema::table('tarjeta_operacion', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('enable')->default('yes');
            $table->dropColumn('lock_session');
        });

        Schema::table('vehiculo_carroceria', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });

        Schema::table('vehiculo_clase', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });

        Schema::table('vehiculo_combustible', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });

        Schema::table('vehiculo_marca', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });

        Schema::table('vehiculo_nivel_servicio', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });

        Schema::table('vehiculo_radio_operacion', function (Blueprint $table) {
            $table->string('enable')->default('yes');
        });
    }
}
