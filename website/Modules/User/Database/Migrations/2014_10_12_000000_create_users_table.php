<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();
            $table->string('username')->unique()->nullable();

            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->integer('profile_id')->unsigned()->nullable();
            $table->enum('profile_type', ['admin', 'doctor', 'patient'])->nullable();

            $table->boolean('is_social')->default(false)->nullable();
            $table->text('social_id')->nullable()->comment('Social ID. Only when is_social is true');
            $table->enum('social_type', ['google', 'apple', 'facebook', 'twitter'])->nullable()->comment('Social Type. Only when is_social is true');
            $table->string('social_email')->nullable()->comment('Social Email; If ANY. Only when is_social is true');
            $table->string('password')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
