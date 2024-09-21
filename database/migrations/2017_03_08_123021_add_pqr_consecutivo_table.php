<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPqrConsecutivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pqr_consecutivo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->string('consecutivo', 6);
            $table->integer('pqr_radicado_id')->unsigned();
            $table->foreign('pqr_radicado_id')->references('id')->on('pqr_radicado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pqr_consecutivo');
    }
}
