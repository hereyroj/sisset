<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatPqrRadicado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pqr_radicado', function (Blueprint $table) {
            //$table->dropColumn('primer_nombre');
            //$table->dropColumn('segundo_nombre');
            //$table->dropColumn('primer_apellido');
            //$table->dropColumn('segundo_apellido');
            $table->string('nombre_usuario');
            $table->longText('descripcion');
            $table->integer('tipo_documento_usuario')->unsigned();
            $table->foreign('tipo_documento_usuario')->references('id')->on('usuario_tipo_documento');
            $table->renameColumn('numero_documento', 'numero_documento_usuario');
            $table->renameColumn('numero_telefono', 'numero_telefono_usuario');
            $table->renameColumn('direccion', 'direccion_usuario');
            $table->renameColumn('departamento_id', 'departamento_usuario');
            $table->renameColumn('municipio_id', 'municipio_usuario');
            $table->renameColumn('correo_electronico', 'correo_electronico_usuario');
            $table->renameColumn('correo_electronico_notificacion', 'correo_notificacion_usuario');
            $table->renameColumn('nro_expediente', 'numero_expediente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pqr_radicado', function (Blueprint $table) {
            $table->string('primer_nombre');
            $table->string('segundo_nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->dropColumn('nombre_usuario');
            $table->dropColumn('descripcion');
            $table->dropForeign(['tipo_documento_usuario']);
            $table->dropColumn('tipo_documento_usuario');
            $table->renameColumn('numero_documento_usuario','numero_documento');
            $table->renameColumn('numero_telefono_usuario','numero_telefono');
            $table->renameColumn('direccion_usuario','direccion');
            $table->renameColumn('departamento_usuario','departamento_id');
            $table->renameColumn('municipio_usuario','municipio_id');
            $table->renameColumn('correo_electronico_usuario','correo_electronico');
            $table->renameColumn('correo_notificacion_usuario','correo_electronico_notificacion');
            $table->renameColumn('numero_expediente','nro_expediente');
        });
    }
}
