<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTramitesSolicitudesModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramite_solicitud_origen', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tramite_solicitud', function (Blueprint $table){
            $table->increments('id');
            $table->string('placa', 6);
            $table->integer('tramite_id')->unsigned()->index();
            $table->integer('vehiculo_clase_id')->unsigned()->index();
            $table->integer('vehiculo_servicio_id')->unsigned()->index();
            $table->integer('tramite_solicitud_origen')->unsigned()->index();
            $table->foreign('tramite_id')->references('id')->on('tramite');
            $table->foreign('vehiculo_clase_id')->references('id')->on('vehiculo_clase');
            $table->foreign('tramite_solicitud_origen')->references('id')->on('tramite_solicitud');
            $table->foreign('vehiculo_servicio_id')->references('id')->on('vehiculo_servicio');
            $table->timestamps();
        });

        Schema::create('tramite_solicitud_radicado', function (Blueprint $table){
            $table->increments('id');
            $table->string('vigencia', 4);
            $table->string('consecutivo', 6);
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->timestamps();
        });

        Schema::create('tramite_solicitud_usuario', function (Blueprint $table){
            $table->increments('id');
            $table->string('nombre_usuario')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('numero_telefonico')->nullable();
            $table->string('tipo_usuario', 1);
            $table->integer('funcionario_id')->unsigned()->index()->nullable();
            $table->integer('tipo_documento_identidad_id')->unsigned()->index()->nullable();
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->foreign('tipo_documento_identidad_id')->references('id')->on('usuario_tipo_documento');
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->timestamps();
        });

        Schema::create('tramite_solicitud_asignacion', function (Blueprint $table){
            $table->dateTime('fecha_reasignacion')->nullable();
            $table->string('motivo_reasignacion')->nullable();
            $table->string('reasignado', 2)->default('NO');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->primary(['funcionario_id', 'tramite_solicitud_id'], 'tramite_solicitud_asignacion_id');
            $table->timestamps();
        });

        Schema::create('tramite_solicitud_estado', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('finaliza_servicio', 2);
            $table->string('requiere_observacion', 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tramite_solicitud_has_estado', function (Blueprint $table){
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->integer('tramite_solicitud_estado_id')->unsigned()->index();
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->foreign('tramite_solicitud_estado_id')->references('id')->on('tramite_solicitud_estado');
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->string('observacion')->nullable();
            $table->primary(['tramite_solicitud_id', 'tramite_solicitud_estado_id'], 'tramite_solicitud_has_estado_id');
            $table->timestamps();
        });

        Schema::create('tramite_solicitud_documentacion', function (Blueprint $table){
            $table->increments('id');
            $table->string('ruta_documento');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->timestamps();
        });

        Schema::create('sustrato', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->uniqued();
            $table->string('consumido', 2)->default('NO');
            $table->timestamps();
        });

        Schema::create('sustrato_anulacion_motivo', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('sustrato_anulacion', function (Blueprint $table){
            $table->integer('sustrato_id')->unsigned()->index()->unique();
            $table->integer('sustrato_anulacion_motivo_id')->unsigned()->index();
            $table->foreign('sustrato_id')->references('id')->on('sustrato');
            $table->foreign('sustrato_anulacion_motivo_id', 'sus_anu_mot_id')->references('id')->on('sustrato_anulacion_motivo');
            $table->string('observacion');
            $table->primary(['sustrato_id', 'sustrato_anulacion_motivo_id'], 'sustrato_anulacio_id');
            $table->timestamps();
        });


        Schema::table('archivo_solicitud', function (Blueprint $table){
            $table->dropForeign(['tramite_id']);
            $table->dropForeign(['folder_id']);
            $table->dropForeign(['user_request_id']);
            $table->dropForeign(['user_delivered_id']);
            $table->dropForeign(['user_patinador_id']);
            $table->dropColumn('tramite_id');
            $table->dropColumn('folder_id');
            $table->dropColumn('user_request_id');
            $table->dropColumn('user_delivered_id');
            $table->dropColumn('user_patinador_id');
            $table->dropColumn('digiturno_code');
            $table->dropColumn('status');
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->integer('funcionario_recibe_id')->unsigned()->index()->nullable();
            $table->foreign('funcionario_recibe_id')->references('id')->on('users');
            $table->integer('funcionario_autoriza_id')->unsigned()->index()->nullable();
            $table->foreign('funcionario_autoriza_id')->references('id')->on('users');
            $table->integer('funcionario_entrega_id')->unsigned()->index()->nullable();
            $table->foreign('funcionario_entrega_id')->references('id')->on('users');
        });

        Schema::table('tramite', function (Blueprint $table){
            $table->string('requiere_sustrato', 2)->default('SI');
            $table->string('require_especie_venal', 2)->default('NO');
            $table->string('solicita_carpeta', 2)->default('SI');
        });

        Schema::create('tramite_funcionario', function (Blueprint $table){
            $table->integer('tramite_id')->unsigned()->index();
            $table->integer('funcionario_id')->unsigned()->index();
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->foreign('tramite_id')->references('id')->on('tramite');
            $table->primary(['tramite_id', 'funcionario_id']);
            $table->timestamps();
        });

        Schema::create('solicitud_rechazo_motivo', function (Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('solicitud_preasignacion_rechazo', function (Blueprint $table){
            $table->integer('sol_preasignacion_id')->unsigned()->index()->unique();
            $table->integer('sol_rechazo_motivo_id')->unsigned()->index();
            $table->foreign('sol_rechazo_motivo_id', 'sol_re_mo_id')->references('id')->on('solicitud_rechazo_motivo');
            $table->foreign('sol_preasignacion_id', 'sol_preasig_id')->references('id')->on('solicitud_preasignacion');
            $table->primary(['sol_preasignacion_id', 'sol_rechazo_motivo_id'], 'solicitud_preasignacion_rechazo_id');
            $table->string('observacion');
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
