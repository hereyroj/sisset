<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoactivoFotoMultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coactivo_foto_multa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cc');
            $table->string('name');
            $table->date('publication_date');
            $table->string('pathArchive');
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
        Schema::drop('coactivo_foto_multa');
    }
}
