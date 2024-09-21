<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_solicitud', function (Blueprint $table) {
            $table->string('sintrat_status')->nullable();
            $table->string('sintrat_observation')->nullable();
            $table->dateTime('sintrat_reviewed')->nullable();
            $table->dropColumn('status_observation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivo_solicitud', function (Blueprint $table) {
            $table->dropColumn('sintrat_status');
            $table->dropColumn('sintrat_observation');
            $table->dropColumn('sintrat_reviewed');
            $table->string('status_observation')->nullable();
        });
    }
}
