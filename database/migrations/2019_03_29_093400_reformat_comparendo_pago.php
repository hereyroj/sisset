<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatComparendoPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('comparendo_pago');
        Schema::drop('comparendo_pago_modalidad');

        Schema::create('cm_pago', function (Blueprint $table){
            $table->increments('id');
            $table->string('valor_intereses');
            $table->string('descuento_intereses')->nullable();
            $table->string('numero_factura')->nullable();
            $table->string('numero_consignacion')->nullable();
            $table->string('valor');
            $table->string('descuento_valor')->nullable();
            $table->string('cobro_adicional')->nullable();
            $table->string('consignacion')->nullable();
            $table->morphs('proceso');
            $table->date('fecha_pago');
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
