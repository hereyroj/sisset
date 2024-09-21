<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChageExpedienteToConsecutivoPQR extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_pqr', function (Blueprint $table) {
            $table->renameColumn('numero_expediente', 'numero_consecutivo');
        });

        Schema::table('gd_pqr_respuesta', function (Blueprint $table) {
            $table->renameColumn('numero_oficio', 'numero_consecutivo');
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
