<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatModuloDigiturno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramite', function (Blueprint $table){
            $table->string('cupl')->nullable();
            $table->string('ministerio')->nullable();
            $table->string('entidad')->nullable();
        });

        Schema::table('post', function (Blueprint $table){
            $table->dropUnique('post_title_unique');
        });

        Schema::table('tramite_servicio_recibo', function (Blueprint $table){
            $table->string('cupl')->nullable()->change();
            $table->string('webservices')->nullable()->change();
            $table->string('consignacion')->nullable()->change();
            $table->string('observacion')->nullable()->change();
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
