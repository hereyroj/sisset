<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatEdictosPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coactivo_comparendo', function (Blueprint $table){
            $table->string('pathArchive')->nullable()->change();
        });

        Schema::table('coactivo_foto_multa', function (Blueprint $table){
            $table->string('pathArchive')->nullable()->change();
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
