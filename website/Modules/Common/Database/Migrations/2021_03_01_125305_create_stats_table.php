<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            // devices based users count
            $table->bigInteger('andriod_users_count')->default(0);
            $table->bigInteger('ios_users_count')->default(0);
            $table->bigInteger('web_users_count')->default(0);

            // social media based users count
            $table->bigInteger('google_users_count')->default(0);
            $table->bigInteger('apple_users_count')->default(0);
            $table->bigInteger('facebook_users_count')->default(0);
            $table->bigInteger('twitter_users_count')->default(0);

            // number of users
            $table->bigInteger('total_teachers_count')->default(0);
            $table->bigInteger('total_parents_count')->default(0);

            $table->bigInteger('total_students_count')->default(0);
            $table->bigInteger('total_paid_students_count')->default(0);
            $table->bigInteger('total_free_students_count')->default(0);

            // number of courses
            $table->bigInteger('total_courses_count')->default(0);
            $table->bigInteger('total_completed_courses_count')->default(0);

            $table->bigInteger('total_online_courses_count')->default(0);
            $table->bigInteger('total_online_paid_courses_count')->default(0);
            $table->bigInteger('total_online_free_courses_count')->default(0);

            $table->bigInteger('total_video_courses_count')->default(0);
            $table->bigInteger('total_video_paid_courses_count')->default(0);
            $table->bigInteger('total_video_free_courses_count')->default(0);
            $table->bigInteger('total_rater_count')->default(0);
            $table->bigInteger('total_rating_count')->default(0);
            $table->bigInteger('total_outlines_count')->default(0);
            $table->bigInteger('total_handouts_count')->default(0);
            $table->bigInteger('total_videos_count')->default(0);
            $table->bigInteger('total_slots_count')->default(0);

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
        Schema::dropIfExists('stats');
    }
}
