<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArchivoSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_solicitud', function (Blueprint $table) {
            $table->dropColumn('sintrat_status');
            $table->dropColumn('sintrat_observation');
            $table->dropColumn('sintrat_reviewed');
            $table->integer('user_patinador_id')->unsigned()->nullable();
            $table->foreign('user_patinador_id')->references('id')->on('users');
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
            $table->string('sintrat_status')->nullable();
            $table->string('sintrat_observation')->nullable();
            $table->string('sintrat_reviewed')->nullable();
            $table->dropColumn('user_patinador_id');
        });
    }
}
