<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculoClaseHasLetraTerminacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo_clase_has_lt', function (Blueprint $table) {
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->integer('letra_terminacion_id')->unsigned()->index();
            $table->foreign('letra_terminacion_id')->references('id')->on('vehiculo_clase_letra_terminacion');
            $table->timestamps();
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
        Schema::drop('vehiculo_clase_has_letra_terminacion');
    }
}
