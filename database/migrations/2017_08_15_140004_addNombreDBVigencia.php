<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNombreDBVigencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::connection('mysql_system')->hasColumn('vigencia', 'nombre_db')){
            Schema::connection('mysql_system')->table('vigencia', function (Blueprint $table){
                $table->string('nombre_db');
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
