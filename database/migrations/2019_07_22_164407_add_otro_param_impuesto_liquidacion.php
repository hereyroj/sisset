<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtroParamImpuestoLiquidacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo_marca_has_clase', function (Blueprint $table) {
            $table->integer('vehiculo_marca_id')->unsigned()->index();
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('vehiculo_marca_id')->references('id')->on('vehiculo_marca');
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
        });

        Schema::table('vehiculo_liq_base_gravable', function (Blueprint $table) {
            $table->string('avaluo')->nullable()->change();
            $table->dropForeign('vehiculo_liq_base_gravable_vehiculo_carroceria_id_foreign');
            $table->dropForeign('vehiculo_liq_base_gravable_vehiculo_clase_id_foreign');
            $table->dropIndex('vehiculo_base_gravable_vehiculo_linea_id_index');
            $table->string('modelo', 4)->nullable()->change();
            $table->string('otro')->nullable();
            $table->string('descripcion')->nullable();
            $table->dropColumn('vehiculo_carroceria_id');
            $table->dropColumn('vehiculo_clase_id');
            $table->integer('vehiculo_marca_id')->unsigned()->index();
            $table->foreign('vehiculo_marca_id')->references('id')->on('vehiculo_marca');
            $table->dropUnique('vehiculo_liq_base_gravable_avaluo_unique');
        });

        Schema::table('vehiculo_linea', function (Blueprint $table) {
            $table->dropUnique('vehiculo_linea_nombre_unique');
            $table->string('watts')->nullable();
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
