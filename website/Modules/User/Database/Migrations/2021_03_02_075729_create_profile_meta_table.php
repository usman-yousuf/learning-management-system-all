<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_metas', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('profile_id')->unsigned();

            $table->integer('total_rating_count')->unsigned()->default(5);
            $table->integer('total_raters_count')->unsigned()->default(1);

            $table->bigInteger('total_lab_tests_count')->default(0);
            $table->bigInteger('total_prescriptions_count')->default(0);

            $table->bigInteger('total_courses_count')->default(0);
            $table->bigInteger('total_chats_count')->default(0);

            $table->bigInteger('total_completed_courses_count')->default(0);
            $table->bigInteger('total_cancelled_courses_count')->default(0);

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
        Schema::dropIfExists('profile_metas');
    }
}
