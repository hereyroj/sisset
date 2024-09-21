<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTramiteLicencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite_licencia', function(Blueprint $table){
            $table->increments('id');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->integer('sustrato_id')->unsigned()->index();
            $table->foreign('sustrato_id')->references('id')->on('sustrato');
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->integer('licencia_categoria_id')->unsigned()->index();
            $table->foreign('licencia_categoria_id')->references('id')->on('licencia_categoria');
            $table->integer('turno_id')->unsigned()->index();
            $table->foreign('turno_id')->references('id')->on('tramite_solicitud_turno');
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
