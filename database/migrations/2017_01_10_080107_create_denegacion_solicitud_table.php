<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDenegacionSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_solicitud_denegacion', function (Blueprint $table) {
            $table->integer('user_denegacion_id')->unsigned();
            $table->foreign('user_denegacion_id')->references('id')->on('users');
            $table->integer('archivo_denegacion_id')->unsigned()->index();
            $table->foreign('archivo_denegacion_id')->references('id')->on('archivo_denegacion');
            $table->integer('archivo_solicitud_id')->unsigned()->index();
            $table->foreign('archivo_solicitud_id')->references('id')->on('archivo_solicitud');
            $table->string('observation')->nullable();
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
        Schema::drop('archivo_solicitud_denegacion');
    }
}
