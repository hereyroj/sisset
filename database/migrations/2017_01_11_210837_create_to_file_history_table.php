<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToFileHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('to_file_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->string('sha1');
            $table->string('mime');
            $table->integer('tarjeta_operacion_id')->unsigned();
            $table->foreign('tarjeta_operacion_id')->references('id')->on('tarjeta_operacion');
            $table->string('status')->default('current');
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
        Schema::drop('to_file_history');
    }
}
