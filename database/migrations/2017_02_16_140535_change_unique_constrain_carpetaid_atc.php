<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUniqueConstrainCarpetaidAtc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_traslado_carpeta', function (Blueprint $table) {
            $table->dropForeign('traslado_carpeta_carpeta_id_foreign');
            $table->dropUnique('traslado_carpeta_carpeta_id_unique');
            $table->foreign('carpeta_id')->references('id')->on('archivo_carpeta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
