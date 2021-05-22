<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();
            $table->integer('creator_id')->unsigned()->nullable();

            $table->BigInteger('quiz_id')->unsigned();
            $table->text('body')->comment('Question Body');
            $table->bigInteger('correct_answer_id')->unsigned()->nullable();
            $table->text('correct_answer')->nullable()->comment('answer to the question in case of test quiz');

            $table->index('quiz_id');
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onUpdate('cascade')->onDelete('cascade');

            $table->index('correct_answer_id');
            // $table->foreign('correct_answer_id')->references('id')->on('question_choices')->onUpdate('cascade')->onDelete('set null');

            $table->index('creator_id');
            // $table->foreign('creator_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('question');
    }
}
