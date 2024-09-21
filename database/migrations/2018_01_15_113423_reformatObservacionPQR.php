<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReformatObservacionPQR extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $vigencias = \DB::connection('mysql_system')->table('vigencia')->select('*')->get();
        foreach ($vigencias as $vigencia){
            \DB::connection('mysql_migration')->disconnect();
            config(['database.connections.mysql_migration.database'=>$vigencia->nombre_db]);
            \DB::connection('mysql_migration')->setDatabaseName($vigencia->nombre_db);
            \DB::connection('mysql_migration')->reconnect();
            Schema::connection('mysql_migration')->table('gd_pqr', function(Blueprint $table){
                $table->longText('descripcion')->nullable()->change();
            });
        }
        Schema::table('gd_pqr', function(Blueprint $table){
            $table->longText('descripcion')->nullable()->change();
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
