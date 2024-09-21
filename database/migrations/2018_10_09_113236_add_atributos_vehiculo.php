<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAtributosVehiculo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculo', function (Blueprint $table) {
            $table->string('color');
            $table->string('puertas',2);
            $table->date('cambio_servicio')->nullable();
            $table->integer('vehiculo_servicio_id')->unsigned()->index()->nullable();
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
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
