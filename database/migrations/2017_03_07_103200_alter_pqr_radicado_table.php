<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPqrRadicadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_radicado', function (Blueprint $table) {
            $table->integer('pqr_tipo_id')->unsigned();
            $table->foreign('pqr_tipo_id')->references('id')->on('pqr_tipo_oficio');
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
            $table->dropColumn('pqr_tipo_id');
        });
    }
}
