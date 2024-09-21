<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNullableColumnsComparendo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comparendo_infractor', function (Blueprint $table){
            $table->string('telefono')->nullable()->change();
            $table->string('direccion')->nullable()->change();
            $table->string('licencia_numero')->nullable()->change();
            $table->date('licencia_fecha_vencimiento')->nullable()->change();
        });

        Schema::table('comparendo_vehiculo', function (Blueprint $table){
            $table->string('tarjeta_operacion')->nullable()->change();
        });

        Schema::table('comparendo',function (Blueprint $table){
            $table->longText('observacion')->nullable()->change();
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
