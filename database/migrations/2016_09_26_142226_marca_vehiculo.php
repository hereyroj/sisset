<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarcaVehiculo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('marca_vehiculo', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name')->unique();
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
        Schema::drop('marca_vehiculo');
    }
}
