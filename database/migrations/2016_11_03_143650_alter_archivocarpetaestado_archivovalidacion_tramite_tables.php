<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArchivocarpetaestadoArchivovalidacionTramiteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_carpeta_estado', function (Blueprint $table) {
            $table->renameColumn('description', 'name');
        });

        Schema::table('archivo_validacion', function (Blueprint $table) {
            $table->renameColumn('description', 'name');
        });

        Schema::table('tramite', function (Blueprint $table) {
            $table->renameColumn('description', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivo_carpeta_estado', function (Blueprint $table) {
            $table->renameColumn('name', 'description');
        });

        Schema::table('archivo_validacion', function (Blueprint $table) {
            $table->renameColumn('name', 'description');
        });

        Schema::table('tramite', function (Blueprint $table) {
            $table->renameColumn('name', 'description');
        });
    }
}
