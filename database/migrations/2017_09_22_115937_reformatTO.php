<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatTO extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo', function (Blueprint $table){
            $table->increments('id');
            $table->string('numero_motor')->unique();
            $table->string('numero_chasis')->unique()->nullable();
            $table->string('placa', 6)->unique();
            $table->string('modelo', 4);
            $table->string('capacidad_pasajeros', 3);
            $table->string('capacidad_toneladas', 4);
            $table->timestamps();
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->integer('vehiculo_carroceria_id')->unsigned()->index();
            $table->integer('vehiculo_marca_id')->unsigned()->index();
            $table->integer('vehiculo_combustible_id')->unsigned()->index();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->foreign('vehiculo_carroceria_id')->references('id')->on('vehiculo_carroceria');
            $table->foreign('vehiculo_marca_id')->references('id')->on('vehiculo_marca');
            $table->foreign('vehiculo_combustible_id')->references('id')->on('vehiculo_combustible');
        });

        Schema::table('tarjeta_operacion', function (Blueprint $table){
            $table->integer('vehiculo_id')->unsigned()->index()->nullable();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculo');
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
