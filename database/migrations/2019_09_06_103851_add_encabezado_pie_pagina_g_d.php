<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEncabezadoPiePaginaGD extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_system')->table('parametros_gestion_documental', function (Blueprint $table) {
            $table->string('encabezado_documento');
            $table->string('pie_documento');
        });

        Schema::connection('mysql_system')->table('parametros_empresa', function ($table) {
            $table->text('empresa_map_coordinates')->nullable()->change();
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
