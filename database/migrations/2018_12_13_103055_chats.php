<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Chats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_room', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->string('password')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_room_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->Index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('chat_room_id')->unsigned()->Index();
            $table->foreign('chat_room_id')->references('id')->on('chat_room');
            $table->boolean('leave')->nullable()->default(false);
            $table->boolean('admin')->nullable()->default(false);
            $table->timestamp('leave_at');
            $table->timestamps();
        });

        Schema::create('chat_message', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->text('message');
            $table->integer('sender_id')->unsigned()->Index();
            $table->foreign('sender_id')->references('id')->on('users');
            $table->integer('reply_id')->unsigned()->Index()->nullable();
            $table->foreign('reply_id')->references('id')->on('chat_message');
            $table->morphs('receiver');
            $table->timestamp('read_at')->nullable();
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
