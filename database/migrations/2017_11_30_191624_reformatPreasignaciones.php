<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatPreasignaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('placa_vehiculo_clase', function (Blueprint $table){
            $table->dropForeign('placa_vehiculo_clase_placa_id_foreign');
            $table->integer('vehiculo_servicio_id')->unsigned()->index();
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
            $table->foreign('placa_id')->references('id')->on('placa');
        });

        Schema::table('vehiculo_clase_has_servicio', function (Blueprint $table){
            $table->dropColumn('max_numeric_range');
            $table->dropColumn('num_letters');
            $table->dropColumn('num_numbers');
            $table->dropColumn('order');
            $table->dropColumn('deleted_at');
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
