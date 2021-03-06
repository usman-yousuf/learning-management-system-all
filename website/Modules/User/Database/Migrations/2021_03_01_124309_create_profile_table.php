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
            $table->integer('approver_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'trans'])->nullable();

            $table->enum('profile_type', ['admin', 'teacher', 'student', 'parent'])->default('student');
            $table->string('profile_image')->nullable();
            $table->text('about')->nullable()->comment('a little about this profile');

            $table->date('dob')->nullable();
            $table->string('interests', 500)->nullable();

            $table->string('phone_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

            $table->string('phone_code_2')->nullable()->comment('Secondary Phone Code');
            $table->string('phone_number_2')->nullable()->comment('Secondary Phone Number');
            $table->timestamp('phone_2_verified_at')->nullable()->comment('Secondary Phone Verfification Status');

            $table->enum('status', ['active', 'suspended', 'terminated'])->default('active');

            // foriegn keys
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->index('approver_id');
            $table->foreign('approver_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('set null');

            $table->index('parent_id');
            $table->foreign('parent_id')->references('id')->on('profiles')->onUpdate('cascade');

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
