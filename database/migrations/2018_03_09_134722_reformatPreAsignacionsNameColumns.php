<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatPreAsignacionsNameColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitud_preasignacion', function (Blueprint $table){
            $table->renameColumn('numero_documento_identidad','numero_documento_solicitante');
            $table->string('nombre_propietario');
            $table->string('numero_documento_propietario');
            $table->string('cedula_propietario')->nullable();
            $table->dropForeign('solicitud_preasignacion_tipo_documento_identidad_id_foreign');
            $table->renameColumn('tipo_documento_identidad_id','tipo_documento_solicitante_id');
            $table->integer('tipo_documento_propietario_id')->unsigned()->index();
            $table->foreign('tipo_documento_solicitante_id')->references('id')->on('usuario_tipo_documento');
            $table->foreign('tipo_documento_propietario_id')->references('id')->on('usuario_tipo_documento');
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
