<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehiculoClaseAddRangosAndLetraTerminacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculo_clase', function (Blueprint $table) {
            $table->integer('maximum_range')->default('99');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehiculo_clase', function (Blueprint $table) {
            $table->dropColumn('maximum_range');
        });
    }
}
