<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueTableSerie extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trd_documento_serie', function (Blueprint $table) {
            $table->dropForeign(['dependencia_id']);
            $table->dropUnique(['dependencia_id']);
            $table->foreign('dependencia_id')->references('id')->on('dependencia');
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
