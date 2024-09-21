<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClaseVehiculoToArchivoCarpeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('archivo_carpeta', function (Blueprint $table) {
            $table->integer('vehiculo_clase_id')->unsigned();
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
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
