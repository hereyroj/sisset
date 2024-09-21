<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatSancionesMorphs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inspeccion_sancion', function (Blueprint $table){
            $table->dropForeign('inspeccion_sancion_comparendo_id_foreign');
            $table->dropColumn('comparendo_id');
            $table->string('proceso_type')->nullable();
            $table->integer('proceso_id')->unsigned()->nullable();
            $table->index(['proceso_type', 'proceso_id']);
            $table->renameColumn('numero_comparendo', 'numero_proceso');
            $table->dropUnique('inspeccion_sancion_numero_comparendo_unique');
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
