<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatVigencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::connection('mysql_system')->hasTable('vigencia')){
            Schema::connection('mysql_system')->create('vigencia', function (Blueprint $table){
                $table->increments('id');
                $table->smallInteger('vigencia')->unique();
                $table->string('impedir_cambios', 2);
                $table->date('inicio_vigencia');
                $table->date('final_vigencia');
            });
        }

        if(!Schema::connection('mysql_system')->hasTable('parametros_pqr')){
            Schema::connection('mysql_system')->create('parametros_pqr', function (Blueprint $table){
                $table->increments('id');
                $table->string('radicado_entrada_consecutivo', 6);
                $table->string('radicado_salida_consecutivo', 6);
                $table->string('editar_pqr_resuelto');
                $table->smallInteger('dias_previo_aviso');
                $table->string('logo_pqr_radicado')->nullable();
                $table->integer('vigencia_id')->unsigned()->index()->unique();
                $table->foreign('vigencia_id')->references('id')->on('vigencia');
            });
        }

        if(!Schema::connection('mysql_system')->hasTable('parametros_tramites')){
            Schema::connection('mysql_system')->create('parametros_tramites', function (Blueprint $table){
                $table->increments('id');
                $table->string('radicado_tramite_consecutivo', 6);
                $table->time('inicio_atencion');
                $table->time('fin_atencion');
                $table->string('turno_rellamado', 2);
                $table->string('turno_preferencial', 2);
                $table->string('turno_transferencia', 2);
                $table->string('turno_logo')->nullable();
                $table->smallInteger('turno_tiempo_espera');
                $table->integer('vigencia_id')->unsigned()->index()->unique();
                $table->foreign('vigencia_id')->references('id')->on('vigencia');
            });
        }

        if(!Schema::connection('mysql_system')->hasTable('parametros_empresa')){
            Schema::connection('mysql_system')->create('parametros_empresa', function (Blueprint $table){
                $table->increments('id');
                $table->integer('vigencia_id')->unsigned()->index()->unique();
                $table->foreign('vigencia_id')->references('id')->on('vigencia');
                $table->string('empresa_logo_menu')->nullable();
                $table->string('empresa_logo')->nullable();
                $table->string('empresa_header')->nullable();
                $table->string('empresa_map_coordinates')->nullable();
                $table->string('empresa_nombre');
            });
        }
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
