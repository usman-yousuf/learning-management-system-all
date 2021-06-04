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
            $table->string('uuid')->unique();

            $table->integer('sender_id')->unsigned();
            $table->index('sender_id');
            $table->foreign('sender_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('receiver_id')->unsigned();
            $table->index('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('profiles')->onDelete('cascade')->onUpdate('cascade');

            $table->string('ref_model_name')->comment('e.g courses');
            $table->bigInteger('ref_id');
            $table->string('additional_ref_model_name')->nullable()->comment('e.g specific handout to purchase');;
            $table->bigInteger('additional_ref_id')->nullable();

            $table->string('noti_type')->comment('notification triggering key. e.g assignment_created, outline added');
            $table->text('noti_text')->comment('Notification Text');

            $table->boolean('is_activity')->default(false);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

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
