<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSustratoLiberacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sustrato_liberacion_motivo', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('sustrato_liberacion', function (Blueprint $table){
            $table->increments('id');
            $table->integer('sus_liberacion_motivo_id')->unsigned()->index();
            $table->foreign('sus_liberacion_motivo_id')->references('id')->on('sustrato_liberacion_motivo');
            $table->integer('sustrato_id')->unsigned()->index();
            $table->foreign('sustrato_id')->references('id')->on('sustrato');
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->string('observacion');
            $table->timestamps();
        });

        Schema::table('sustrato_anulacion', function (Blueprint $table){
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
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
