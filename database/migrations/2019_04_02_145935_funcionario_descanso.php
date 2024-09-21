<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FuncionarioDescanso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionario_descanso_motivo', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->tinyInteger('minutes');
            $table->timestamps();
        });

        Schema::create('funcionario_descanso', function(Blueprint $table){
            $table->increments('id');
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->integer('fun_descanso_motivo_id')->unsigned()->index();
            $table->foreign('fun_descanso_motivo_id')->references('id')->on('funcionario_descanso_motivo');
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
