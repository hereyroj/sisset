<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPqrAsignacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqr_asignacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('funcionario_id')->unsigned();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->integer('dependencia_id')->unsigned();
            $table->foreign('dependencia_id')->references('id')->on('dependencia');
            $table->integer('usuario_asignado_id')->unsigned();
            $table->foreign('usuario_asignado_id')->references('id')->on('users');
            $table->integer('pqr_radicado_id')->unsigned();
            $table->foreign('pqr_radicado_id')->references('id')->on('pqr_radicado');
            $table->tinyInteger('estado')->default(1);
            $table->string('descripcion_reasignacion')->nullable();
            $table->dateTime('fecha_reasignacion')->nullable();
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
        Schema::drop('pqr_asignacion');
    }
}
