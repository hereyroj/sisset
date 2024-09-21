<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveArchivoSolicitudColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_solicitud', function (Blueprint $table){
            $table->dropForeign('archivo_solicitud_funcionario_recibe_id_foreign');
            $table->dropForeign('archivo_solicitud_funcionario_autoriza_id_foreign');
            $table->dropForeign('archivo_solicitud_funcionario_entrega_id_foreign');
            $table->dropColumn(['funcionario_recibe_id','funcionario_autoriza_id','funcionario_entrega_id','folder_delivered','folder_returned','request_aproved','request_date']);
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
