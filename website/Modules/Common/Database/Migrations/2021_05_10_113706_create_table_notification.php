<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id')->unsigned(false);

            $table->integer('sender_id')->unsigned();
            $table->index('sender_id');
            $table->foreign('sender_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('receiver_id')->unsigned();
            $table->index('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('model_id')->comment('notification triggering model ID');
            $table->string('noti_model')->comment('notification triggering model');
            $table->string('noti_type')->comment('notification triggering key');
            $table->text('noti_text')->comment('Notification Text');

            $table->boolean('is_activity')->default(false);
            $table->boolean('is_read')->default(false);

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
        Schema::dropIfExists('notifications');
    }
}
