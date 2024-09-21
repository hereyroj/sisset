<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreasignableColumnVehiculoClase extends Migration
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
            if(!Schema::connection('mysql_migration')->hasColumn('vehiculo_clase', 'pre_asignable')){
                Schema::connection('mysql_migration')->table('vehiculo_clase', function(Blueprint $table){
                    $table->string('pre_asignable')->default('SI');
                });
            }
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
