<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemofrmatParametrosSistema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql_system')->table('vigencia', function (Blueprint $table){
            $table->dropColumn('nombre_db');
        });

        Schema::connection('mysql_system')->create('parametros_gestion_documental', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vigencia_id')->unsigned()->index();
            $table->foreign('vigencia_id')->references('id')->on('vigencia');
            $table->string('radicado_entrada_consecutivo');
            $table->string('radicado_salida_consecutivo');
            $table->timestamps();
        });

        /*
         * Migracion de parametros de radicado de parametros_pqr a parametros_gestion_documental
         */
        $parametrosPQR = \App\sistema_parametros_pqr::all();
        foreach ($parametrosPQR as $parametros) {
            \App\sistema_parametros_gd::create([
                'radicado_entrada_consecutivo' => $parametros->radicado_entrada_consecutivo,
                'radicado_salida_consecutivo' => $parametros->radicado_salida_consecutivo,
                'vigencia_id' => $parametros->vigencia_id
            ]);
        }

        Schema::connection('mysql_system')->table('parametros_pqr', function (Blueprint $table){
            $table->dropColumn('radicado_entrada_consecutivo');
            $table->dropColumn('radicado_salida_consecutivo');
        });

        Schema::connection('mysql_system')->table('parametros_tramites', function (Blueprint $table){
            $table->dropColumn('radicado_tramite_consecutivo');
        });

        Schema::rename('gd_pqr_radicado_entrada','gd_radicado_entrada');

        Schema::rename('gd_pqr_radicado_salida','gd_radicado_salida');

        Schema::table('gd_radicado_entrada', function (Blueprint $table){
            $table->string('origen_type');
            $table->integer('origen_id')->unsigned()->index();
        });

        Schema::table('gd_radicado_salida', function (Blueprint $table){
            $table->string('origen_type');
            $table->integer('origen_id')->unsigned()->index();
        });

        /*
         * Migracion de los indices de pqr a origen_id
         */
        $radicados_entradas = \App\gd_radicado_entrada::all();
        foreach($radicados_entradas as $radicado){
            $radicado->origen_type = 'App\\gd_pqr';
            $radicado->origen_id = $radicado->gd_pqr_id;
            $radicado->save();
        } 

        $radicados_salidas = \App\gd_radicado_salida::all();
        foreach ($radicados_salidas as $radicado) {
            $radicado->origen_type = 'App\\gd_pqr';
            $radicado->origen_id = $radicado->gd_pqr_id;
            $radicado->save();
        } 

        /*
         * Eliminacion de indices de pqr
         */
        Schema::table('gd_radicado_entrada', function (Blueprint $table){
            $table->dropForeign('gd_pqr_radicado_entrada_gd_pqr_id_foreign');
            $table->dropColumn('gd_pqr_id');
        });

        Schema::table('gd_radicado_salida', function (Blueprint $table){
            $table->dropForeign('gd_pqr_radicado_salida_gd_pqr_id_foreign');
            $table->dropColumn('gd_pqr_id');
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
