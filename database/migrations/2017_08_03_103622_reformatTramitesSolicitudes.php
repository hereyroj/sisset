<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatTramitesSolicitudes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('tramite_solicitud', function (Blueprint $table) {
            $table->dropForeign(['tramite_solicitud_origen_id']);
            $table->foreign('tramite_solicitud_origen_id')->references('id')->on('tramite_solicitud_origen');
        });

        Schema::table('archivo_solicitud', function (Blueprint $table) {
            $table->dropForeign(['funcionario_recibe_id']);
            $table->dropForeign(['funcionario_autoriza_id']);
            $table->dropForeign(['funcionario_entrega_id']);
            $table->dropColumn('funcionario_recibe_id');
            $table->dropColumn('funcionario_autoriza_id');
            $table->dropColumn('funcionario_entrega_id');
            $table->dropColumn('request_date');
            $table->dropColumn('request_aproved');
            $table->dropColumn('folder_delivered');
            $table->dropColumn('folder_returned');
        });*/

        Schema::table('tramite_solicitud_usuario', function (Blueprint $table){
            $table->dropForeign('tramite_solicitud_usuario_tipo_documento_identidad_id_foreign');
            $table->dropColumn('tipo_documento_identidad_id');
            $table->dropColumn('tipo_usuario');
            $table->dropForeign(['funcionario_id']);
            $table->dropColumn('funcionario_id');
            $table->string('nombre_usuario')->nullable(false)->change();
            $table->string('numero_documento')->nullable(false)->change();
        });

        Schema::table('tramite_solicitud_usuario', function (Blueprint $table){
            $table->integer('tipo_documento_identidad_id')->unsigned()->index();
            $table->foreign('tipo_documento_identidad_id')->references('id')->on('usuario_tipo_documento');
        });

        Schema::create('archivo_carpeta_prestamo', function (Blueprint $table){
            $table->increments('id');
            $table->integer('archivo_carpeta_id')->unsigned()->index();
            $table->integer('funcionario_recibe_id')->unsigned()->index();
            $table->integer('funcionario_autoriza_id')->unsigned()->index();
            $table->integer('funcionario_entrega_id')->unsigned()->index();
            $table->dateTime('fecha_entrega');
            $table->dateTime('fecha_devolucion')->nullable();
            $table->foreign('archivo_carpeta_id')->references('id')->on('archivo_carpeta');
            $table->foreign('funcionario_recibe_id')->references('id')->on('users');
            $table->foreign('funcionario_autoriza_id')->references('id')->on('users');
            $table->foreign('funcionario_entrega_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::table('archivo_solicitud', function (Blueprint $table){
            $table->integer('archivo_carpeta_prestamo_id')->unsigned()->index()->nullable();
            $table->foreign('archivo_carpeta_prestamo_id')->references('id')->on('archivo_carpeta_prestamo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
        public
        function down()
        {
            //
        }
    }
