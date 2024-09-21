<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComparendoIdSancion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspeccion_sancion', function (Blueprint $table){
            $table->integer('comparendo_id')->unsigned()->index()->nullable();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
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
