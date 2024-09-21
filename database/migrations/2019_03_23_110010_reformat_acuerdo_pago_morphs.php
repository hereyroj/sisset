<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatAcuerdoPagoMorphs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('acuerdo_pago_comparendo');

        Schema::create('acuerdo_pago_proceso', function(Blueprint $table){
            $table->morphs('proceso');
            $table->integer('acuerdo_pago_id')->unsigned()->index();
            $table->foreign('acuerdo_pago_id')->references('id')->on('acuerdo_pago');
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
