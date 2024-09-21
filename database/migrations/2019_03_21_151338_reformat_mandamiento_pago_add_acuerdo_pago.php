<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatMandamientoPagoAddAcuerdoPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mandamiento_pago', function(Blueprint $table){
           $table->morphs('proceso');
           $table->dropForeign('mandamiento_pago_comparendo_id_foreign');
           $table->dropColumn('comparendo_id');
        });

        Schema::table('mandamiento_notificacion', function(Blueprint $table){
            $table->string('pantallazo_runt')->nullable();
        });

        Schema::table('ma_notificacion_entrega', function(Blueprint $table){
            $table->string('documento_entrega')->nullable();
        });

        Schema::table('ma_notificacion_medio', function(Blueprint $table){
            $table->dropForeign('ma_notificacion_medio_empresa_transporte_id_foreign');
            $table->dropColumn('empresa_transporte_id');
        });

        Schema::table('ma_notificacion_medio', function(Blueprint $table){
            $table->integer('empresa_mensajeria_id')->unsigned()->index()->nullable();
            $table->foreign('empresa_mensajeria_id')->references('id')->on('empresa_mensajeria');
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
