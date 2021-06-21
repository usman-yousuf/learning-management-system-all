<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('sender_id')->unsigned()->comment('Sender ID');
            $table->bigInteger('chat_id')->unsigned()->comment('Chat ID')->nullable();

            $table->string('message')->comment('Message Body');
            $table->bigInteger('tagged_message_id')->unsigned()->comment('Reply to Message ID')->nullable();

            $table->index('sender_id');
            $table->foreign('sender_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

            $table->index('chat_id');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade')->onUpdate('cascade');

            $table->index('tagged_message_id');
            $table->foreign('tagged_message_id')->references('id')->on('chat_messages')->onDelete('cascade')->onUpdate('set null');

            $table->softDeletes();
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
        Schema::dropIfExists('chat_messages');
    }
}
