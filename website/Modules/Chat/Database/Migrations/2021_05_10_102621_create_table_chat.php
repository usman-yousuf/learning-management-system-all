<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('parent_id')->unsigned()->comment('The one who initiated chat');

            $table->enum('type', ['single', 'group'])->default('single');
            $table->string('title')->nullable()->comment('Title of Chat if Applicable');
            $table->integer('last_message_id')->unsigned()->nullable()->comment('Last Message ID');

            $table->bigInteger('total_members_count')->default(0);
            $table->bigInteger('total_messages_count')->default(0);
            $table->bigInteger('total_medias_count')->default(0);

            $table->index('parent_id');
            $table->foreign('parent_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

            $table->index('last_message_id');

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
        Schema::dropIfExists('chats');
    }
}
