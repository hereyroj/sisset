<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TramiteTipoSustrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite', function (Blueprint $table){
           $table->integer('tipo_sustrato_id')->unsigned()->index()->nullable();
           $table->foreign('tipo_sustrato_id')->references('id')->on('tipo_sustrato');
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
