<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatPlaca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('placa', function (Blueprint $table){
            $table->dropForeign('especie_venal_vehiculo_clase_id_foreign');
            $table->dropColumn('vehiculo_clase_id');
        });

        Schema::create('placa_vehiculo_clase', function (Blueprint $table){
            $table->integer('placa_id')->unsigned()->index();
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->foreign('placa_id')->references('id')->on('vehiculo_clase');
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
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
