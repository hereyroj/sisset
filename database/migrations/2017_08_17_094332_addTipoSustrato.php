<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoSustrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_sustrato', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('sustrato', function (Blueprint $table){
            $table->renameColumn('name', 'numero');
            $table->integer('tipo_sustrato_id')->unsigned()->index();
            $table->foreign('tipo_sustrato_id')->references('id')->on('tipo_sustrato');
        });

        Schema::table('sustrato', function (Blueprint $table){
            $table->string('numero')->unique()->change();
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
