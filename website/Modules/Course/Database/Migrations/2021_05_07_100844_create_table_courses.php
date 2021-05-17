<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();
            $table->enum('nature', ['video', 'online']);

            $table->integer('teacher_id')->unsigned();
            $table->integer('course_category_id')->unsigned();

            $table->text('description')->nullable();
            $table->string('course_image')->nullable();

            $table->boolean('is_course_free')->default(true);
            $table->boolean('is_handout_free')->default(true);
            $table->decimal('price_usd', 20, 2)->nullable()->default(false);
            $table->decimal('discount_usd', 20, 2)->nullable()->default(false);
            $table->decimal('price_pkr', 20, 2)->nullable()->default(false);
            $table->decimal('discount_pkr', 20, 2)->nullable()->default(false);

            $table->decimal('total_duration', 20, 2)->nullable()->default(false);

            $table->boolean('is_approved')->default(false);

            $table->bigInteger('students_count')->default(false);
            $table->bigInteger('paid_students_count')->default(false);
            $table->bigInteger('free_students_count')->default(false);

            $table->index('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

            $table->index('course_category_id');
            $table->foreign('course_category_id')->references('id')->on('course_categories')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('courses');
    }
}
