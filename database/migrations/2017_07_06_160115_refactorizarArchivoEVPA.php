<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorizarArchivoEVPA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especie_venal_estado', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 6)->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehiculo_servicio', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehiculo_clase_has_servicio', function(Blueprint $table){
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->integer('vehiculo_servicio_id')->unsigned()->index();
            $table->string('max_numeric_range', 3);
            $table->string('num_letters', 1);//se tendra en cuenta cuando se vaya a ingresar rangos de especies venales
            $table->string('num_numbers', 1);//se tendra en cuenta cuando se vaya a ingresar rangos de especies venales
            $table->string('order', 1)->default('L');//L=Letras y N=Numeros
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
        });

        Schema::table('vehiculo_clase', function (Blueprint $table){
           $table->dropColumn('maximum_range');
        });

        Schema::create('especie_venal', function(Blueprint $table){
            $table->increments('id');
            $table->string('name', 6)->unique();
            $table->integer('especie_venal_estado_id')->unsigned();
            $table->integer('vehiculo_clase_id')->unsigned();
            $table->integer('vehiculo_servicio_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('especie_venal_estado_id')->references('id')->on('especie_venal_estado');
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
        });



        Schema::table('archivo_carpeta', function (Blueprint $table){
            $table->renameColumn('estado_id', 'archivo_carpeta_estado_id');
            $table->integer('vehiculo_servicio_id')->unsigned()->nullable();
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
