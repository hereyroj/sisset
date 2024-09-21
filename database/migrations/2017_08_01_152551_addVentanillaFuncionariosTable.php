<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVentanillaFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_solicitud', function (Blueprint $table){
            $table->dropForeign(['tramite_solicitud_origen']);
            $table->renameColumn('tramite_solicitud_origen', 'tramite_solicitud_origen_id');
            $table->foreign('tramite_solicitud_origen_id')->references('id')->on('tramite_solicitud');
        });

        Schema::create('ventanilla_funcionario', function (Blueprint $table){
            $table->integer('ventanilla_id')->unsigned()->index();
            $table->integer('funcionario_id')->unsigned()->index();
            $table->date('fecha_ocupacion');
            $table->dateTime('fecha_retiro')->nullable();
            $table->string('libre', 2)->default('NO');
            $table->foreign('ventanilla_id')->references('id')->on('ventanilla');
            $table->foreign('funcionario_id')->references('id')->on('users');
            $table->primary(['ventanilla_id', 'funcionario_id'], 'ventanilla_funcionario_id');
        });

        Schema::table('tramite_solicitud_asignacion', function (Blueprint $table){
            $table->integer('ventanilla_id')->unsigned()->index();
            $table->foreign('ventanilla_id')->references('id')->on('ventanilla');
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
