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
            $table->string('correo_administrador');
            $table->text('descripcion');
            $table->string('horario');
            $table->string('keywords');
            $table->string('firma_inspector')->nullable();
            $table->string('nombre_inspector');
            $table->string('facebook');
            $table->string('twitter');
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
