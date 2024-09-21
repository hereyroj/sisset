<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatSustratos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('sustrato_anulacion');

        Schema::create('sustrato_anulacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sustrato_id')->unsigned()->index();
            $table->integer('sustrato_anulacion_motivo_id')->unsigned()->index();
            $table->foreign('sustrato_id')->references('id')->on('sustrato');
            $table->foreign('sustrato_anulacion_motivo_id')->references('id')->on('sustrato_anulacion_motivo');
            $table->string('observacion');
            $table->timestamps();
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
