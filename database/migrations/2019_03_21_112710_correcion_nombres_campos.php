<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrecionNombresCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mandamiento_medio', function(Blueprint $table){
            $table->renameColumn('require_guia', 'requiere_guia');
        });

        Schema::table('ma_notificacion_tipo', function (Blueprint $table){
            $table->dropColumn('orden');
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
