<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatComparendoUbicacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('comparendo_ubicacion');

        Schema::table('comparendo', function (Blueprint $table) {
            $table->string('barrio_vereda');
            $table->string('direccion');
            $table->string('valor')->change();
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
