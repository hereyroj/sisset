<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEVPreAsignacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especie_venal_preasignacion', function (Blueprint $table){
            $table->integer('solicitud_preasignacion_id')->unsigned()->index();
            $table->integer('especie_venal_id')->unsigned()->index();
            $table->dateTime('fecha_preasignacion')->nullable()->default(null);
            $table->dateTime('fecha_liberacion')->nullable()->default(null);
            $table->dateTime('fecha_matricula')->nullable()->default(null);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('solicitud_preasignacion_id')->references('id')->on('solicitud_preasignacion');
            $table->foreign('especie_venal_id')->references('id')->on('especie_venal');
        });

        Schema::table('especie_venal', function (Blueprint $table){
            $table->dropColumn('fecha_preasignacion');
            $table->dropColumn('fecha_matricula');
            $table->dropForeign(['especie_venal_estado_id']);
            $table->dropColumn('especie_venal_estado_id');
        });

        Schema::drop('especie_venal_estado');
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
