<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMunicipioUniquename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('municipio', function (Blueprint $table) {

            $table->dropUnique('municipio_name_unique');
           // $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('municipio', function (Blueprint $table) {
            $table->unique('name');
        });
    }
}
