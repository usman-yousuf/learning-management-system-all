<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();
            $table->integer('user_id')->unsigned();

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'trans'])->nullable();

            $table->enum('profile_type', ['admin', 'teacher', 'student', 'parent'])->default('student');
            $table->string('profile_image')->nullable();

            $table->date('dob')->nullable();

            $table->string('phone_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

            $table->string('phone_code_2')->nullable()->comment('Secondary Phone Code');
            $table->string('phone_number_2')->nullable()->comment('Secondary Phone Number');
            $table->timestamp('phone_2_verified_at')->nullable()->comment('Secondary Phone Verfification Status');

            $table->string('position')->nullable();
            $table->index('position');

            $table->enum('status', ['active', 'suspended', 'terminated'])->default('active');

            // foriegn keys
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
