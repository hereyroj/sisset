<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPqrRadicadoAddMedio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_radicado', function (Blueprint $table) {
            $table->integer('pqr_medio_traslado_id')->unsigned();
            $table->foreign('pqr_medio_traslado_id')->references('id')->on('pqr_medio_traslado');
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
