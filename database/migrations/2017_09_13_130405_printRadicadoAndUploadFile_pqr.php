<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrintRadicadoAndUploadFilePqr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_pqr', function (Blueprint $table){
            $table->string('documento_radicado')->unique()->nullable();
        });

        Schema::table('gd_pqr_radicado_entrada', function (Blueprint $table){
            $table->dropColumn('vigencia');
            $table->dropColumn('consecutivo');
            $table->string('numero')->unique();
        });

        Schema::table('gd_pqr_radicado_salida', function (Blueprint $table){
            $table->dropColumn('vigencia');
            $table->dropColumn('consecutivo');
            $table->string('numero')->unique();
        });

        Schema::table('gd_pqr_respuesta', function (Blueprint $table){
           $table->renameColumn('documento_respuesta', 'documento_radicado');
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
