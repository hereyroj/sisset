<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrasladoCarpetaTableUsuarioAutoriza extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traslado_carpeta', function (Blueprint $table) {
            $table->dropColumn('autorizacion');
            $table->integer('usuario_autoriza_id')->unsigned();
            $table->foreign('usuario_autoriza_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traslado_carpeta', function (Blueprint $table) {
            $table->string('autorizacion');
            $table->dropColumn('usuario_autoriza_id');
        });
    }
}
