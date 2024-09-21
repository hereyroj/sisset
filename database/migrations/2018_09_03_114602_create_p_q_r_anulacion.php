<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePQRAnulacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_pqr_anulacion_motivo',function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('gd_pqr_anulacion',function (Blueprint $table){
            $table->increments('id');
            $table->integer('gd_pqr_id')->unsigned()->index()->unique();
            $table->integer('gd_pqr_anulacion_mo_id')->unsigned()->index();
            $table->foreign('gd_pqr_id')->references('id')->on('gd_pqr');
            $table->foreign('gd_pqr_anulacion_mo_id')->references('id')->on('gd_pqr_anulacion_motivo');
            $table->text('observation');
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
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
