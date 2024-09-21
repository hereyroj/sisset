<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatParametrosEmpresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_system')->table('parametros_empresa', function (Blueprint $table) {
            $table->string('empresa_sigla');
            $table->string('empresa_direccion');
            $table->string('empresa_telefono');
            $table->string('empresa_web');
            $table->string('empresa_correo_contacto');
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
