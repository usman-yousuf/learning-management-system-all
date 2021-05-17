<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();
            $table->integer('course_id')->unsigned();

            $table->integer('student_id')->unsigned();

            $table->enum('star_rating', ['1', '2', '3', '4', '5'])->default('5');
            $table->string('body')->nullable();

            $table->index('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');

            $table->index('student_id');
            $table->foreign('student_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('reviews');
    }
}
