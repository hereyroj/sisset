<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoSaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gd_pqr', function (Blueprint $table) {
            $table->date('limite_respuesta')->nullable()->default(null)->change();
        });

        Schema::create('gd_pqr_saliente', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gd_pqr_id')->unsigned()->index()->unique();
            $table->foreign('gd_pqr_id')->references('id')->on('gd_pqr');
            $table->string('documento_radicado');
            $table->date('fecha_radicado');
            $table->string('documento_contestacion')->nullable();
            $table->date('fecha_constestacion')->nullable();
            $table->date('fecha_recibido')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
