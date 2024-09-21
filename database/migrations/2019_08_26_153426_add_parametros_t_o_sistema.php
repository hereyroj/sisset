<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParametrosTOSistema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_system')->create('parametros_to', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vigencia_id')->unsigned()->index();
            $table->string('consecutivo_inicial');
            $table->string('marca_agua');
            $table->string('valor_unitario');
            $table->foreign('vigencia_id')->references('id')->on('vigencia');
            $table->timestamps();
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
