<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChatMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_medias', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();
            $table->bigInteger('chat_id')->unsigned()->comment('Chat ID');
            $table->bigInteger('chat_message_id')->unsigned()->comment('Chat Message ID');

            $table->string('title')->comment('Media Title')->nullable();
            $table->string('path')->comment('File URL');
            $table->string('thumbnail')->nullable()->comment('Thumbnail of the media file');
            $table->string('ratio')->comment('Image ratio; if applicable')->nullable();
            $table->string('type')->comment('Media Type; e.g image, video, audio, document');
            $table->string('size')->nullable()->comment('Media File Size');

            $table->integer('profile_id')->unsigned()->comment('the one who uploaded this file');

            $table->index('chat_id');
            $table->foreign('chat_id')->references('id')->on('chats')->onUpdate('cascade')->onDelete('cascade');

            $table->index('chat_message_id');
            $table->foreign('chat_message_id')->references('id')->on('chat_messages')->onUpdate('cascade')->onDelete('cascade');

            $table->index('profile_id');
            $table->foreign('profile_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('chat_media');
    }
}
