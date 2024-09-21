<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPqrHasRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_pqr_respuesta', function (Blueprint $table){
            $table->integer('gd_pqr_respondido_id')->unsigned()->index();
            $table->integer('gd_pqr_respuesta_id')->unsigned()->index();
            $table->foreign('gd_pqr_respondido_id')->references('id')->on('gd_pqr');
            $table->foreign('gd_pqr_respuesta_id')->references('id')->on('gd_pqr');
        });

        Schema::table('gd_pqr', function (Blueprint $table){
            $table->dropForeign(['gd_pqr_respuesta_id']);
            $table->dropColumn('gd_pqr_respuesta_id');
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
