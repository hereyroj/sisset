<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntregaPQR extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gd_pqr_entrega', function(Blueprint $table){
            $table->increments('id');
            $table->integer('gd_pqr_id')->unique()->unsigned()->index();
            $table->date('fecha_entrega');
            $table->string('documento_entrega')->unique();
            $table->timestamps();
            $table->foreign('gd_pqr_id')->references('id')->on('gd_pqr');
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
