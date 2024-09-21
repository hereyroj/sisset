<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTramiteSolicitudRecibos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite_solicitud_recibo', function (Blueprint $table){
            $table->increments('id');
            $table->string('cupl');
            $table->string('webservices');
            $table->string('observacion');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
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
