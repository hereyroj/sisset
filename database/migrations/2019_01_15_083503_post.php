<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Post extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('uuid')->unique();
            $table->longText('post');
            $table->longText('tags')->nullable();
            $table->mediumText('resume')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamp('published_date');
            $table->timestamp('unpublished_date')->nullable();
            $table->integer('post_category_id')->unsigned()->index()->nullable();
            $table->foreign('post_category_id')->references('id')->on('post_category');
            $table->integer('post_status_id')->unsigned()->index()->nullable();
            $table->foreign('post_status_id')->references('id')->on('post_status');
            $table->integer('author_id')->unsigned()->index()->nullable();
            $table->foreign('author_id')->references('id')->on('users');
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
