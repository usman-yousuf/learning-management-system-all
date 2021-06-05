<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQuizzes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('course_id')->unsigned();
            $table->bigInteger('slot_id')->unsigned();
            $table->integer('assignee_id')->unsigned();

            $table->string('title');
            $table->text('description');
            $table->enum('type', ['test', 'mcqs', 'boolean'])->default('test');

            $table->date('due_date')->nullable();
            $table->date('extended_date')->nullable();

            $table->decimal('duration_mins', 20, 2)->nullable()->default(false);
            $table->decimal('students_count', 20, 2)->nullable()->default(false)->comment('Number of students attending this test');

            $table->index('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');

            $table->index('slot_id');
            $table->foreign('slot_id')->references('id')->on('course_slots')->onUpdate('cascade')->onDelete('cascade');

            $table->index('assignee_id');
            $table->foreign('assignee_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('quizzes');
    }
}
