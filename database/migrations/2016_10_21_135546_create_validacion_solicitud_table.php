<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidacionSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validacion_solicitud', function (Blueprint $table) {
            $table->integer('user_revision_id')->unsigned();
            $table->foreign('user_revision_id')->references('id')->on('users');
            $table->integer('archivo_validacion_id')->unsigned()->index();
            $table->foreign('archivo_validacion_id')->references('id')->on('archivo_validacion');
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
       Schema::drop('validacion_solicitud');
    }
}
