<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCourseOutlines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_outlines', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('course_id')->unsigned();

            $table->string('title');
            $table->string('duration_hrs')->nullable()->default(0);
            $table->string('duration_mins')->nullable()->default(0);

            $table->index('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('course_outlines');
    }
}
