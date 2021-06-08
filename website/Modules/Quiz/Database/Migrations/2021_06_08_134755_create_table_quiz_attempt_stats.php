<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuizAttemptStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_attempt_stats', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('student_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->BigInteger('quiz_id')->unsigned();

            $table->integer('total_questions')->default(0);
            $table->integer('total_marks')->default(0);
            $table->integer('marks_per_question')->default(0);
            $table->integer('total_correct_answers')->default(0);
            $table->integer('total_wrong_answers')->default(0);

            $table->index('student_id');
            $table->foreign('student_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

            $table->index('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');

            $table->index('quiz_id');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('quiz_attempt_stats');
    }
}
