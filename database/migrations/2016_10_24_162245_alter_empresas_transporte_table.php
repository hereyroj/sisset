<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmpresasTransporteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas_transporte', function (Blueprint $table) {
            $table->string('nit');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas_transporte', function (Blueprint $table) {
            $table->dropColumn('nit');
            $table->dropColumn('email');
            $table->dropColumn('telephone');
        });
    }
}
