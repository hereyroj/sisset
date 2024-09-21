<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiculoClaseLetraTerminacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo_clase_letra_terminacion', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->char('name', 1)->unique();
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
        Schema::drop('vehiculo_clase_letra_terminacion');
    }
}
