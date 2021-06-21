<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStudentQuizAttempts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_quiz_answers', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('student_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->BigInteger('quiz_id')->unsigned();
            $table->BigInteger('question_id')->unsigned();

            $table->text('answer_body')->nullable()->comment('Answer to the question in case of test question');
            $table->bigInteger('selected_answer_id')->unsigned()->nullable()->comment('Answer Choice ID in case of MCQs or Boolean');
            $table->enum('status', ['marked', 'pending'])->default('pending');

            $table->index('student_id');
            $table->foreign('student_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

            $table->index('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');

            $table->index('quiz_id');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onUpdate('cascade')->onDelete('cascade');

            $table->index('question_id');
            $table->foreign('question_id')->references('id')->on('questions')->onUpdate('cascade')->onDelete('cascade');

            $table->index('selected_answer_id');
            // $table->foreign('selected_answer_id')->references('id')->on('question_choices')->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('student_quiz_answers');
    }
}
