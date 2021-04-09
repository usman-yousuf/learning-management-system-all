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
        Schema::enableForeignKeyConstraints();

        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('uuid')->unique();
            $table->integer('user_id')->unsigned();

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['male', 'female', 'trans'])->nullable();
            $table->enum('profile_type', ['admin', 'doctor', 'patient'])->default('patient');
            $table->string('profile_image')->nullable();

            $table->date('dob')->nullable();
            $table->text('bio')->nullable();
            $table->string('specialization')->nullable();
            $table->string('age')->nullable();


            $table->string('phone_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

            // $table->string('ethnicity')->nullable();

            // $table->string('nok')->comment('Next of Kin')->nullable();
            // $table->string('emergency_contact')->comment('Emergency Contact Number')->nullable();

            // $table->string('license_number')->comment('license Number')->nullable();
            // $table->index('license_number');

            // $table->string('license_authority')->comment('license Authority')->nullable();
            // $table->index('license_authority');

            // $table->string('license_organization')->comment('License Provider')->nullable();
            // $table->index('license_organization');

            $table->string('position')->nullable();
            $table->index('position');

            // $table->string('social_security')->nullable();
            // $table->text('organizations')->nullable();

            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            // $table->boolean('is_convicted')->default(false);
            // $table->boolean('is_policy_holder')->default(false);
            // $table->string('language')->nullable();
            $table->enum('status', ['active', 'suspended', 'terminated'])->default('active');

            // foriegn keys
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();


        // Schema::table('profiles', function (Blueprint $table) {
        //     $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        //     // $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        // });
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
