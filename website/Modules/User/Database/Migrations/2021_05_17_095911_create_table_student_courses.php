<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStudentCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_courses', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('course_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->bigInteger('slot_id')->unsigned()->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('joining_date')->nullable();

            $table->index('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');

            $table->index('student_id');
            $table->foreign('student_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

            $table->index('slot_id');
            // $table->foreign('slot_id')->references('id')->on('course_slots')->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('student_courses');
    }
}
