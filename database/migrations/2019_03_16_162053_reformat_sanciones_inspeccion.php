<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatSancionesInspeccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspeccion_sancion', function($table){
            $table->string('numero_comparendo')->unique()->nullable();
            $table->string('consecutivo')->unique()->nullable();
        });

        Schema::table('mandamiento_pago', function($table){
            $table->dropColumn('documento_sancion');
            $table->dropColumn('fecha_sancion');
            $table->dropColumn('consecutivo_sancion');
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
