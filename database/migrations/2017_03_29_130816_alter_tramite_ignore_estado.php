<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTramiteIgnoreEstado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite', function (Blueprint $table) {
            $table->tinyInteger('ignora_restriccion')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tramite', function (Blueprint $table) {
            $table->dropColumn('ignora_restriccion');
        });
    }
}
