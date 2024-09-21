<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatInspeccionNotificacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('comparendo_notificacion', 'inspeccion_notificacion');

        Schema::create('inspeccion_notificacion_tipo', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('inspeccion_notificacion', function (Blueprint $table){
            $table->integer('inspeccion_notificacion_tipo_id')->unsigned()->index();
            $table->foreign('inspeccion_notificacion_tipo_id')->references('id')->on('inspeccion_notificacion_tipo');
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
