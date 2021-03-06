<?php

namespace Modules\User\Database\Seeders;

use Modules\User\Entities\Profile;
use Modules\User\Entities\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::statement('truncate table users');
        \DB::statement('truncate table profiles');
        // \DB::table('users')->truncate();
        // \DB::table('profiles')->truncate();

        User::create([
            'uuid' => \Str::uuid(),
            'username' => 'admin',
            'email' => 'admin@lms.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'profile_id' => '1',
            'profile_type' => 'admin',
            'is_social' => '0',
            'social_type' => null,
            'social_email' => null,
            'password' => bcrypt('admin123'),
            'created_at' => date('Y-m-d H:i:s'),
            'remember_token' => '0',
        ]);

        Profile::create([
            'uuid' => \Str::uuid(),
            'first_name' => 'LMS',
            'last_name' => 'Administration',
            'gender' => 'male',
            'user_id' => '1',
            'profile_type' => 'admin',
            'dob' => null,
            'phone_code' => null,
            'phone_number' => null,
            'phone_verified_at' => date('Y-m-d H:i:s'),

            'created_at' => date('Y-m-d H:i:s'),
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
