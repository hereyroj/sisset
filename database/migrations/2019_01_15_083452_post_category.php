<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('parent_category_id')->unsigned()->index()->nullable();
            $table->foreign('parent_category_id')->references('id')->on('post_category');
            $table->timestamps();
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
