<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFacturacionTramiteSolicitudLicencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_licencia', function(Blueprint $table){
            $table->string('consignacion')->nullable();
            $table->string('cupl')->nullable();
            $table->string('webservices')->nullable();
            $table->string('numero_consignacion')->unique();
            $table->string('numero_cupl')->unique();
            $table->string('numero_sintrat')->unique();
        });

        Schema::create('tramite_licencia_categoria', function (Blueprint $table) {
            $table->integer('tramite_licencia_id')->unsigned()->index();
            $table->integer('licencia_categoria_id')->unsigned()->index();
            $table->foreign('tramite_licencia_id')->references('id')->on('tramite_licencia');
            $table->foreign('licencia_categoria_id')->references('id')->on('licencia_categoria');
        });

        Schema::table('tramite_licencia', function (Blueprint $table) {
            $table->dropForeign('tramite_licencia_licencia_categoria_id_foreign');
            $table->dropColumn('licencia_categoria_id');
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
