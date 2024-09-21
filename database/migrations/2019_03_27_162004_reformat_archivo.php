<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatArchivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivo_carpeta_traslado', function(Blueprint $table){
            $table->renameColumn('nombre_funcionario_autoriza', 'num_certificado_runt');
        });

        Schema::table('archivo_carpeta_cancelacion', function(Blueprint $table){
            $table->dropColumn('nro_acto_administrativo');
        });

        Schema::table('archivo_carpeta_prestamo', function(Blueprint $table){
            $table->softDeletes();
        });

        Schema::table('tramite_servicio_recibo', function(Blueprint $table){
            $table->string('consignacion');
            $table->string('numero_consignacion');
            $table->string('numero_sintrat');
            $table->string('numero_cupl');
        });

        Schema::table('archivo_solicitud_motivo', function(Blueprint $table){
            $table->boolean('priorizar')->default(false);
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
