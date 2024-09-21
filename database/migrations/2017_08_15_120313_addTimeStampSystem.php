<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeStampSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::connection('mysql_system')->hasColumn('vigencia', 'created_at')){
            Schema::connection('mysql_system')->table('vigencia', function (Blueprint $table){
                $table->timestamps();
            });
        }

        if(!Schema::connection('mysql_system')->hasColumn('parametros_pqr', 'created_at')){
            Schema::connection('mysql_system')->table('parametros_pqr', function (Blueprint $table){
                $table->timestamps();
            });
        }

        if(!Schema::connection('mysql_system')->hasColumn('parametros_tramites', 'created_at')){
            Schema::connection('mysql_system')->table('parametros_tramites', function (Blueprint $table){
                $table->timestamps();
            });
        }

        if(!Schema::connection('mysql_system')->hasColumn('parametros_empresa', 'created_at')){
            Schema::connection('mysql_system')->table('parametros_empresa', function (Blueprint $table){
                $table->timestamps();
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
