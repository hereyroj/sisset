<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArchivoCarpetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_carpeta', function (Blueprint $table) {
            $table->dropColumn('inventoried');
            $table->integer('estado_id')->unsigned();
            $table->foreign('estado_id')->references('id')->on('carpeta_estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivo_carpeta', function (Blueprint $table) {
            $table->string('inventoried');
            $table->dropColumn('estado_id');
        });
    }
}
