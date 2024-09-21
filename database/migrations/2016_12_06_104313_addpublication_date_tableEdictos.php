<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddpublicationDateTableEdictos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coactivo_edicto', function (Blueprint $table) {
            $table->date('publication_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coactivo_edicto', function (Blueprint $table) {
            $table->dropColumn('publication_date');
        });
    }
}
