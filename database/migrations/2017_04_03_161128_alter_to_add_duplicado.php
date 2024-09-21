<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterToAddDuplicado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarjeta_operacion', function (Blueprint $table) {
            $table->tinyInteger('duplicado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarjeta_operacion', function (Blueprint $table) {
            $table->dropColumn('duplicado');
        });
    }
}
