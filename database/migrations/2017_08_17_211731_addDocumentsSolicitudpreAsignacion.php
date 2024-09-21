<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentsSolicitudpreAsignacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitud_preasignacion', function (Blueprint $table){
            $table->string('manifiesto_importacion')->unique()->nullable()->change();
            $table->string('factura_compra')->unique()->nullable();
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
