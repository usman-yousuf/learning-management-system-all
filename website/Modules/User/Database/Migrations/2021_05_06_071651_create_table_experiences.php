<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExperiences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();

            $table->integer('profile_id')->unsigned();

            $table->string('job_exp');
            $table->string('teaching_exp');
            $table->string('image')->nullable();

            $table->index('profile_id');
            $table->foreign('profile_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('experiences');
    }
}
