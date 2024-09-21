<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InspeccionNotificacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comparendo_notificacion', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_publicacion');
            $table->date('fecha_desfijacion');
            $table->string('numero_documento');
            $table->string('nombre_notificado');
            $table->string('documento_notificacion');
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
        //
    }
}
