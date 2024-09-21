<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatTramitesTurnos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_solicitud_turno', function (Blueprint $table){
            $table->string('llamado', 2)->nullable()->change();
            $table->dateTime('fecha_llamado')->nullable();

            $table->string('atendido', 2)->nullable()->change();
            $table->dateTime('fecha_atencion')->nullable();

            $table->string('anulado', 2)->nullable()->change();
            $table->dateTime('fecha_anulacion')->nullable();

            $table->string('vencido', 2)->nullable()->change();
            $table->dateTime('fecha_vencimiento')->nullable();

            $table->integer('turno_rellamado_id')->unsigned()->index()->nullable();
            $table->foreign('turno_rellamado_id')->references('id')->on('tramite_solicitud_turno');

            $table->string('preferente', 2)->nullable();
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
