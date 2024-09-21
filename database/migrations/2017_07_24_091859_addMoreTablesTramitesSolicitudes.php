<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreTablesTramitesSolicitudes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite_solicitud_turno', function (Blueprint $table){
            $table->increments('id');
            $table->string('turno', 6);
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->string('llamado')->nullable();
            $table->string('atendido')->nullable();
            $table->string('vencido')->nullable();
            $table->timestamps();
            $table->string('anulado', 2)->nullable();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
        });

        Schema::table('tramite_solicitud_asignacion', function (Blueprint $table){
            $table->integer('tramite_solicitud_turno_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_turno_id')->references('id')->on('tramite_solicitud_turno');
        });

        Schema::table('tramite', function (Blueprint $table){
            $table->string('codigo', 3);
        });

        Schema::create('ventanilla', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('codigo');
            $table->timestamps();
        });

        Schema::create('ventanilla_tramite', function (Blueprint $table){
            $table->integer('ventanilla_id')->unsigned()->index();
            $table->integer('tramite_id')->unsigned()->index();
            $table->foreign('ventanilla_id')->references('id')->on('ventanilla');
            $table->foreign('tramite_id')->references('id')->on('tramite');
            $table->primary(['ventanilla_id', 'tramite_id'], 'ventanilla_tramite_id');
            $table->timestamps();
        });

        Schema::drop('tramite_funcionario');
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
