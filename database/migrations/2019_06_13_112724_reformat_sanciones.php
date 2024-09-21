<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatSanciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('inspeccion_sancion', 'notificacion_aviso');

        Schema::rename('inspeccion_sancion_tipo', 'notificacion_aviso_tipo');

        Schema::table('notificacion_aviso', function (Blueprint $table) {
            $table->dropForeign('inspeccion_sancion_inspeccion_sancion_tipo_id_foreign');
            $table->renameColumn('inspeccion_sancion_tipo_id', 'not_aviso_tipo_id');
            $table->foreign('not_aviso_tipo_id')->references('id')->on('notificacion_aviso_tipo');
            $table->dropColumn('consecutivo');
            $table->dropColumn('proceso_type');
            $table->dropColumn('proceso_id');
            $table->renameColumn('nombre_sancionado', 'nombre_notificado');
            $table->renameColumn('documento_sancion', 'documento_notificacion');
        });

        Schema::create('sancion', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_sancion');
            $table->integer('cantidad_salarios');
            $table->string('cuantia_salarios');
            $table->string('documento')->nullable();
            $table->morphs('proceso');
            $table->string('numero_proceso');
            $table->string('numero')->unique();
            $table->timestamps();
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
