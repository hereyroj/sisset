<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentoPropietarioTramiteServicio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite_servicio', function (Blueprint $table) {
            $table->string('documento_propietario');
        });

        Schema::table('tramite_solicitud_atencion', function (Blueprint $table) {
            $table->string('observacion')->nullable()->change();;
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
