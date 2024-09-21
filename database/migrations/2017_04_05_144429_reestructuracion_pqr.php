<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReestructuracionPqr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Limpieza de columnas y cambio de nomenclatura para un mejor entendimiento
         */
        Schema::table('pqr_radicado', function (Blueprint $table) {
            $table->dropForeign(['tipo_oficio_id']);
            $table->dropForeign(['pqr_tipo_id']);
            $table->dropColumn('tipo_oficio_id');
            $table->foreign('pqr_tipo_id')->references('id')->on('pqr_tipo');
            $table->renameColumn('nombre_usuario', 'nombre_peticionario');
            $table->renameColumn('tipo_documento_usuario', 'tipo_documento_peticionario');
            $table->renameColumn('municipio_usuario', 'municipio_peticionario');
            $table->renameColumn('departamento_usuario', 'departamento_peticionario');
            $table->renameColumn('correo_notificacion_usuario', 'correo_notificacion_peticionario');
            $table->renameColumn('correo_electronico_usuario', 'correo_electronico_peticionario');
            $table->renameColumn('numero_telefono_usuario', 'numero_telefono_peticionario');
            $table->renameColumn('numero_documento_usuario', 'numero_documento_peticionario');
            $table->renameColumn('direccion_usuario', 'direccion_peticionario');
            $table->dropColumn('primer_nombre');
            $table->dropColumn('segundo_nombre');
            $table->dropColumn('primer_apellido');
            $table->dropColumn('segundo_apellido');
            $table->renameColumn('pqr_tipo_id', 'gd_pqr_clase_id');
            $table->renameColumn('pqr_medio_traslado_id', 'gd_medio_traslado_id');
        });

        /*
         * gd_pqrradicadoentrada es reemplazado por pqr_radicado_x
         */
        Schema::drop('pqr_consecutivo');
        Schema::drop('pqr_tipo_consecutivo');

        /*
         * Nuevas tablas de radicado_x
         */
        Schema::create('gd_pqr_radicado_salida', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->string('consecutivo', 6);
            $table->integer('gd_pqr_id')->unsigned()->unique();
            $table->foreign('gd_pqr_id')->references('id')->on('pqr_radicado');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gd_pqr_radicado_entrada', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->string('consecutivo', 6);
            $table->integer('gd_pqr_id')->unsigned()->unique();
            $table->foreign('gd_pqr_id')->references('id')->on('pqr_radicado');
            $table->timestamps();
            $table->softDeletes();
        });

        /*
         * Se eliminan para llevar a cabo la implementación de las tablas de retención documental
         */
        Schema::drop('pqr_tipo_oficio');
        Schema::drop('clase_tipo_oficio');

        /*
         * Se crean las nuevas tablas para las tablas de retención documental
         */
        Schema::create('trd_documento_serie', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dependencia_id')->unsigned()->unique();
            $table->foreign('dependencia_id')->references('id')->on('dependencia');
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('trd_documento_subserie', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trd_documento_serie_id')->unsigned();
            $table->foreign('trd_documento_serie_id')->references('id')->on('trd_documento_serie');
            $table->string('name')->unique();
            $table->string('archivo_gestion', 1)->nullable();
            $table->string('archivo_central', 1)->nullable();
            $table->string('conservacion_total', 1)->nullable();
            $table->string('eliminacion', 1)->nullable();
            $table->string('digitalizar', 1)->nullable();
            $table->string('seleccion', 1)->nullable();
            $table->longText('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('trd_documento_tipo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trd_documento_subserie_id')->unsigned();
            $table->foreign('trd_documento_subserie_id')->references('id')->on('trd_documento_subserie');
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        /*
         * Se crea la tabla de clasificación del PQR. Esta se diligencia manualmente ya que no es posible realizar esta clasificación desde el formulario público
         */
        Schema::create('gd_pqr_clasificacion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gd_pqr_id')->unsigned()->unique();
            $table->foreign('gd_pqr_id')->references('id')->on('pqr_radicado');
            $table->integer('trd_documento_tipo_id')->unsigned();
            $table->foreign('trd_documento_tipo_id')->references('id')->on('trd_documento_tipo');
            $table->timestamps();
            $table->softDeletes();
        });

        /*
         * Cambio de nomenclatura
         */
        Schema::table('pqr_asignacion', function (Blueprint $table) {
            $table->renameColumn('pqr_radicado_id', 'gd_pqr_id');
        });

        Schema::table('pqr_respuesta', function (Blueprint $table) {
            $table->renameColumn('pqr_radicado_id', 'gd_pqr_id');
            $table->renameColumn('pqr_asignacion_id', 'gd_pqr_asignacion_id');
        });

        /*
         * Se determinan los días hábiles
         */
        Schema::table('pqr_tipo', function (Blueprint $table) {
            $table->string('dia_clase');
            $table->string('dia_cantidad', 3);
        });

        /*
         * Cambio de nomenclatura
         */
        Schema::rename('pqr_radicado', 'gd_pqr');
        Schema::rename('pqr_respuesta', 'gd_pqr_respuesta');
        Schema::rename('pqr_medio_traslado', 'gd_medio_traslado');
        Schema::rename('pqr_tipo', 'gd_pqr_clase');
        Schema::rename('pqr_asignacion', 'gd_pqr_asignacion');

        /*
         * Código de clasificación de dependencia de acuerdo a las tablas de retención documental
         */
        Schema::table('dependencia', function (Blueprint $table) {
            $table->string('codigo', 3)->unique()->nullable();
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
