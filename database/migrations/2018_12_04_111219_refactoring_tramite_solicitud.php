<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactoringTramiteSolicitud extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  
        Schema::create('tramite_grupo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code', 4)->unique();            
            $table->timestamps();
        });

        Schema::table('tramite_solicitud', function (Blueprint $table) {
            $table->tinyInteger('placas');
            $table->dropColumn('placa');
            $table->dropForeign(['tramite_id']);
            $table->dropForeign(['vehiculo_clase_id']);
            $table->dropForeign(['vehiculo_servicio_id']);
            $table->text('observacion');
            $table->integer('tramite_grupo_id')->unsigned()->index();
            $table->foreign('tramite_grupo_id')->references('id')->on('tramite_grupo');
        });

        Schema::table('tramite_solicitud', function (Blueprint $table) {
            $table->dropColumn('tramite_id');
            $table->dropColumn('vehiculo_clase_id');
            $table->dropColumn('vehiculo_servicio_id');
        });

        Schema::create('tramite_grupo_has_tramite', function (Blueprint $table) {
            $table->integer('tramite_grupo_id')->unsigned()->index();
            $table->foreign('tramite_grupo_id')->references('id')->on('tramite_grupo');
            $table->integer('tramite_id')->unsigned()->index();
            $table->foreign('tramite_id')->references('id')->on('tramite');
        });

        Schema::table('tramite', function (Blueprint $table) {
            $table->dropColumn('especifica_placa');
            $table->dropColumn('codigo');            
        });

        Schema::create('tramite_solicitud_has_tramite', function (Blueprint $table) {
            $table->integer('tramite_solicitud_id')->unsigned()->index();
            $table->foreign('tramite_solicitud_id')->references('id')->on('tramite_solicitud');
            $table->integer('tramite_id')->unsigned()->index();
            $table->foreign('tramite_id')->references('id')->on('tramite');
        });

        Schema::drop('ventanilla_tramite');

        Schema::create('ventanilla_tramite_grupo', function (Blueprint $table) {
            $table->integer('tramite_grupo_id')->unsigned()->index();
            $table->foreign('tramite_grupo_id')->references('id')->on('tramite_grupo');
            $table->integer('ventanilla_id')->unsigned()->index();
            $table->foreign('ventanilla_id')->references('id')->on('ventanilla');
            $table->tinyInteger('prioridad');
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
