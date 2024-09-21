<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatPQRCoSa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_pqr_saliente', function (Blueprint $table){
            $table->dropColumn('documento_contestacion');
            $table->dropColumn('fecha_constestacion');
            $table->dropColumn('fecha_recibido');
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
