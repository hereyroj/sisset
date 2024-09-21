<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehiculoClaseAddRequiredLetter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculo_clase', function (Blueprint $table) {
            $table->string('required_letter')->default('no');
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
            $table->dropColumn('required_letter');
        });
    }
}
