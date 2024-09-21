<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCarpetaCancelacionAddMotivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_carpeta_cancelacion', function (Blueprint $table) {
            $table->integer('motivo_id')->unsigned();
            $table->foreign('motivo_id')->references('id')->on('archivo_cancelacion_motivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivo_carpeta_cancelacion', function (Blueprint $table) {
            $table->dropForeign('archivo_cancelacion_motivo_motivo_id_foreign');
            $table->dropColumn('motivo_id');
        });
    }
}
