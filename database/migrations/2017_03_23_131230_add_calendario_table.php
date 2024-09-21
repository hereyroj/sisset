<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalendarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendario', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dia');
            $table->date('fecha');
            $table->boolean('laboral');
            $table->boolean('fin_de_semana');
            $table->boolean('feriado');
            $table->string('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calendario');
    }
}
