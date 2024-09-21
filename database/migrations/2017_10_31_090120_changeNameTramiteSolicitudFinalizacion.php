<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNameTramiteSolicitudFinalizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_solicitud_finalizacion', function (Blueprint $table){
           $table->renameColumn('especie_venal_id', 'placa_id');
           $table->integer('funcionario_id')->unsigned()->index();
           $table->foreign('funcionario_id')->references('id')->on('users');
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
