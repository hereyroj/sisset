<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNormatividad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('normativa_tipo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('normativa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero');
            $table->date('fecha_expedicion');
            $table->string('objeto');
            $table->string('documento');
            $table->integer('normativa_tipo_id')->unsigned()->index();
            $table->foreign('normativa_tipo_id')->references('id')->on('normativa_tipo');
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
