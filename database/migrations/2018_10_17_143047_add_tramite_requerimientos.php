<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTramiteRequerimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite_requerimiento', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longtext('description');
            $table->integer('tramite_id')->unsigned()->index();
            $table->foreign('tramite_id')->references('id')->on('tramite');
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
