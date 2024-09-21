<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetCoExCoInPQR extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_pqr', function (Blueprint $table) {
            $table->dropForeign('pqr_radicado_tipo_documento_usuario_foreign');
            $table->dropColumn('tipo_documento_peticionario');
            $table->dropColumn('nombre_peticionario');
            $table->dropForeign('pqr_radicado_municipio_id_foreign');
            $table->dropColumn('municipio_peticionario');
            $table->dropForeign('pqr_radicado_departamento_id_foreign');
            $table->dropColumn('departamento_peticionario');
            $table->dropColumn('correo_notificacion_peticionario');
            $table->dropColumn('correo_electronico_peticionario');
            $table->dropColumn('numero_telefono_peticionario');
            $table->dropColumn('direccion_peticionario');
            $table->dropColumn('numero_documento_peticionario');
            $table->string('tipo_pqr', 4);
            $table->string('pdf')->nullable();
        });

        Schema::create('gd_pqr_peticionario', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gd_pqr_id')->unsigned()->index()->unique();
            $table->foreign('gd_pqr_id')->references('id')->on('gd_pqr');
            $table->integer('funcionario_id')->unsigned()->index()->nullable();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->integer('dependencia_id')->unsigned()->index()->nullable();
            $table->foreign('dependencia_id')->references('id')->on('dependencia');
            $table->integer('tipo_documento_id')->unsigned()->index()->nullable();
            $table->foreign('tipo_documento_id')->references('id')->on('usuario_tipo_documento');
            $table->integer('departamento_id')->unsigned()->index()->nullable();
            $table->foreign('departamento_id')->references('id')->on('departamento');
            $table->integer('municipio_id')->unsigned()->index()->nullable();
            $table->foreign('municipio_id')->references('id')->on('municipio');
            $table->string('correo_notificacion')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('numero_telefono')->nullable();
            $table->string('direccion_residencia')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('nombre_completo')->nullable();
            $table->string('tipo_usuario', 1);
            $table->timestamps();
            $table->softDeletes();
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
