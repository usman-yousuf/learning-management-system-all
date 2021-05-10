<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseSlots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_slots', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('course_id')->unsigned();

            $table->datetime('slot_start');
            $table->datetime('slot_end');
            $table->string('day_nums')->comment('Comma Seperated day numbers. 0 is Monday');

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
        Schema::dropIfExists('course_slots');
    }
}
