<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComparendoInmovilizacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comparendo_inmovilizacion_tipo', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('comparendo_inmovilizacion', function (Blueprint $table){
            $table->integer('comparendo_id')->unsigned()->index();
            $table->integer('inmovilizacion_tipo_id')->unsigned()->index();
            $table->string('observacion');
            $table->timestamps();
            $table->foreign('comparendo_id')->references('id')->on('comparendo');
            $table->foreign('inmovilizacion_tipo_id')->references('id')->on('comparendo_inmovilizacion_tipo');
        });

        Schema::table('comparendo', function (Blueprint $table){
            $table->dropColumn('opcionInmovilizacion');
            $table->dropColumn('observacionInmovilizacion');
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
