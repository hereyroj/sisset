<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatVehiculoLiquidacionAvaluos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_solicitud', function (Blueprint $table) {
            $table->renameColumn('placas', 'servicios');
        });

        Schema::table('vehiculo_liq_base_gravable', function (Blueprint $table) {
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->integer('vehiculo_carroceria_id')->unsigned()->index()->nullable();
            $table->foreign('vehiculo_carroceria_id')->references('id')->on('vehiculo_carroceria');    
            $table->integer('vehiculo_linea_id')->unsigned()->index()->nullable()->change();  
            $table->string('grupo')->nullable();  
            $table->string('tonelaje')->nullable();  
            $table->string('pasaje')->nullable();   
        });

        Schema::create('vehiculo_clase_grupo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->string('name');  
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->integer('vehiculo_marca_id')->unsigned()->index();
            $table->foreign('vehiculo_marca_id')->references('id')->on('vehiculo_marca');    
            $table->timestamps();
        });

        Schema::create('vehiculo_cilindraje_grupo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->string('name');  
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->integer('desde');
            $table->integer('hasta');  
            $table->timestamps();
        });

        Schema::create('vehiculo_bateria_tipo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('vehiculo', function (Blueprint $table) {
            $table->integer('vehiculo_bateria_tipo_id')->unsigned()->index()->nullable();
            $table->foreign('vehiculo_bateria_tipo_id')->references('id')->on('vehiculo_bateria_tipo');
            $table->string('bateria_capacidad_watts')->nullable();
        });

        Schema::create('vehiculo_bateria_grupo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->string('name');  
            $table->integer('vehiculo_bateria_tipo_id')->unsigned()->index();
            $table->foreign('vehiculo_bateria_tipo_id')->references('id')->on('vehiculo_bateria_tipo');
            $table->integer('desde');
            $table->integer('hasta');  
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
