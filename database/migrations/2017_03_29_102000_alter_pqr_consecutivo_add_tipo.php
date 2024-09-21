<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPqrConsecutivoAddTipo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_consecutivo', function (Blueprint $table) {
            $table->integer('pqr_tipo_consecutivo_id')->unsigned();
            $table->foreign('pqr_tipo_consecutivo_id')->references('id')->on('pqr_tipo_consecutivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqr_consecutivo', function (Blueprint $table) {
            $table->dropForeign(['pqr_tipo_consecutivo_id']);
            $table->dropColumn('pqr_tipo_consecutivo_id');
        });
    }
}
