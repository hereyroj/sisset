<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDerechosEmpresaImpuesto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculo_liquidacion_vigencia', function (Blueprint $table){
            $table->float('derechos_entidad');
        });

        Schema::table('vehiculo_propietario', function (Blueprint $table){
            $table->string('correo_electronico')->unique()->nullable();
        });

        Schema::table('vehiculo_liquidacion', function (Blueprint $table){
            $table->float('derechos_entidad');
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
