<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArchivoCarpetaAddRadicado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_carpeta', function (Blueprint $table) {
            $table->char('radicado', 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivo_carpeta', function (Blueprint $table) {
            $table->dropColumn('radicado', 1);
        });
    }
}
