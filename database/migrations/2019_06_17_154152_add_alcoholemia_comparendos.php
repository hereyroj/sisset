<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlcoholemiaComparendos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comparendo_infraccion', function (Blueprint $table) {
            $table->integer('smdlv');    
        });

        Schema::create('comparendo_alcoholemia', function(Blueprint $table){
            $table->increments('id');
            $table->tinyInteger('grado');
            $table->string('valor');
            $table->timestamps();
        });

        Schema::connection('mysql_system')->table('vigencia', function (Blueprint $table) {
            $table->string('salario_minimo');
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
