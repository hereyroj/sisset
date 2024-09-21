<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatTurno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_solicitud_turno', function (Blueprint $table) {
            $table->dropForeign('tramite_solicitud_turno_turno_rellamado_id_foreign');
            $table->dateTime('fecha_rellamado')->nullable();
        });

        Schema::table('tramite_solicitud_turno', function (Blueprint $table) {
            $table->dropColumn('turno_rellamado_id');
            $table->dropColumn('anulado');
        });

        Schema::table('tramite_solicitud_asignacion', function (Blueprint $table) {
            $table->dropColumn('fecha_reasignacion');
            $table->dropColumn('motivo_reasignacion');
            $table->dropColumn('reasignado');
        });

        Schema::table('tramite_solicitud', function (Blueprint $table) {
            $table->text('observacion')->nullable()->change();
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
