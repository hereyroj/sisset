<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatVehiculoLiquidacionVigencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehiculo_liquidacion_vigencia',function (Blueprint $table){
            $table->dropColumn('porcentaje_motocicleta');
            $table->dropColumn('porcentaje_automovil');
            $table->dropColumn('porcentaje_carga');
            $table->dropColumn('porcentaje_pasajeros');
            $table->float('impuesto_publico');
        });

        Schema::table('vehiculo_liquidacion_descuento', function (Blueprint $table){
            $table->integer('ve_li_vi_id')->nullable()->unsigned()->index();
            $table->foreign('ve_li_vi_id')->references('id')->on('vehiculo_liquidacion_vigencia');
            $table->date('vigente_desde');
            $table->date('vigente_hasta');
            $table->dropColumn('fecha_inicio');
            $table->dropColumn('fecha_fin');
        });

        Schema::table('vehiculo_liquidacion', function (Blueprint $table){
            $table->float('valor_impuesto');
            $table->float('valor_avaluo');
            $table->string('codigo', 10)->unique();
            $table->string('anulada')->default('NO');
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
