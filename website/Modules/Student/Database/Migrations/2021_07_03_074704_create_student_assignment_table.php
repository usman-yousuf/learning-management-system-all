<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_assignments', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();
            $table->integer('course_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->bigInteger('assignment_id')->unsigned();

            $table->string('media');
            $table->enum('status', ['marked', 'pending'])->default('pending');
            $table->decimal('obtained_marks', 20, 2)->nullable()->default(false);


            $table->index('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');

            $table->index('student_id');
            $table->foreign('student_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

            $table->index('assignment_id');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('student_assignments');
    }
}
