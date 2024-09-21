<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoleReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_report', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->integer('report_id')->unsigned()->index();
            $table->foreign('report_id')->references('id')->on('report');
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
        Schema::drop('role_report');
    }
}
