<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TipoVehiculo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tipo_vehiculo', function (Blueprint $table) {
          $table->increments('id');
          $table->string('description')->unique();
          $table->string('enable');
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
        Schema::drop('tipo_vehiculo');
    }
}
