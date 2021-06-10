<?php

namespace Modules\User\Database\Seeders;

use Modules\User\Entities\Profile;
use Modules\User\Entities\User;
use Illuminate\Database\Seeder;

class TeacherUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // \DB::table('users')->truncate();
        // \DB::table('profiles')->truncate();

        User::create([
            'uuid' => \Str::uuid(),
            'username' => 'teacher',
            'email' => 'teacher@lms.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'profile_id' => '2',
            'profile_type' => 'teacher',
            'is_social' => '0',
            'social_type' => null,
            'social_email' => null,
            'password' => bcrypt('teacher123'),
            'created_at' => date('Y-m-d H:i:s'),
            'remember_token' => '0',
        ]);

        Profile::create([
            'uuid' => \Str::uuid(),
            'first_name' => 'Teacher',
            'last_name' => 'LMS',
            'gender' => 'male',
            'user_id' => '2',
            'approver_id' => '1', // main admin has apprvoed it
            'profile_type' => 'teacher',
            'dob' => null,
            'phone_code' => null,
            'phone_number' => null,
            'phone_verified_at' => date('Y-m-d H:i:s'),

            'created_at' => date('Y-m-d H:i:s'),
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
