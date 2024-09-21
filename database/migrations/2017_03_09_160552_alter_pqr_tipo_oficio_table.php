<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPqrTipoOficioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_tipo_oficio', function (Blueprint $table) {
            $table->integer('clase_id')->unsigned();
            $table->foreign('clase_id')->references('id')->on('clase_tipo_oficio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqr_tipo_oficio', function (Blueprint $table) {
            $table->dropColumn('clase_id');
        });
    }
}
