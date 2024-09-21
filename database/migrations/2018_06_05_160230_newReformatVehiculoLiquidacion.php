<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewReformatVehiculoLiquidacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculo_liquidacion', function (Blueprint $table){
            $table->bigInteger('valor_total')->change();
            $table->bigInteger('valor_avaluo')->change();
            $table->string('vencido', 2)->nullable();
            $table->string('anulado', 2)->nullable();
        });

        Schema::create('vehiculo_liquidacion_pago', function (Blueprint $table){
            $table->increments('id');
            $table->integer('vehiculo_liquidacion_id')->index()->unsigned();
            $table->string('numero_consignacion');
            $table->bigInteger('valor_consignacion');
            $table->string('consignacion')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
