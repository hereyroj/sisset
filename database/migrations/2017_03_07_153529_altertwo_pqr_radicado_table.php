<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltertwoPqrRadicadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_radicado', function (Blueprint $table) {
            $table->string('segundo_nombre')->nullable()->change();
            $table->string('segundo_apellido')->nullable()->change();
            $table->string('numero_telefono')->nullable()->change();
            $table->string('correo_electronico')->nullable()->change();
            $table->string('correo_electronico_notificacion')->nullable()->change();
            $table->dropColumn('anexo_pdf');
            $table->dropColumn('anexo_jpeg');
            $table->dropColumn('anexo_png');
            $table->string('anexos')->nullable();
            $table->date('limite_respuesta');
            $table->dateTime('previo_aviso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqr_radicado', function (Blueprint $table) {
            $table->string('segundo_nombre')->change();
            $table->string('segundo_apellido')->change();
            $table->string('numero_telefono')->change();
            $table->string('correo_electronico')->change();
            $table->string('correo_electronico_notificacion')->change();
            $table->string('anexo_pdf')->nullable();
            $table->string('anexo_jpeg')->nullable();
            $table->string('anexo_png')->nullable();
            $table->dropColumn('anexos')->nullable();
            $table->dropColumn('limite_respuesta');
            $table->dropColumn('previo_aviso');
        });
    }
}
