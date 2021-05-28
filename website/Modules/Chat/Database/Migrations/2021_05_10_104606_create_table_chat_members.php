<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChatMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_members', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->bigInteger('chat_id')->unsigned()->comment('Chat ID');

            $table->integer('member_id')->unsigned()->comment('Chat Participant ID');
            $table->enum('member_status', ['active', 'inactive', 'left'])->default('active');
            $table->enum('member_role', ['admin', 'teacher', 'student', 'parent'])->default('admin')->nullable();

            $table->bigInteger('unread_messages_count')->default(0);

            $table->index('chat_id');
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade')->onUpdate('cascade');

            $table->index('member_id');
            $table->foreign('member_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('chat_members');
    }
}
