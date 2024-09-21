<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArchivoCarpetaTraslado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_traslado_carpeta', function (Blueprint $table) {
            $table->string('nombre_funcionario_autoriza');
            $table->dropForeign('traslado_carpeta_usuario_autoriza_id_foreign');
            $table->dropColumn('usuario_autoriza_id');
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
