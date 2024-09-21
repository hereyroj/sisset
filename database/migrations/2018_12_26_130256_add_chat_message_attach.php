<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChatMessageAttach extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_message_attach', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->string('name');
            $table->string('original_name');
            $table->string('mime');
            $table->integer('chat_message_id')->unsigned()->index();
            $table->foreign('chat_message_id')->references('id')->on('chat_message');
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
