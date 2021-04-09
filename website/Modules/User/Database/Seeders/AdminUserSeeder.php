<?php

namespace Modules\User\Database\Seeders;

use App\Models\Profile;
use App\Models\User;
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
        // \DB::table('users')->truncate();
        // \DB::table('profiles')->truncate();

        User::create([
            'uuid' => \Str::uuid(),
            'username' => 'admin',
            'email' => 'admin@telemedicine.com',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'profile_id' => '1',
            'profile_type' => 'admin',
            'is_social' => '0',
            'social_type' => null,
            'password' => bcrypt('admin123'),
            'created_at' => date('Y-m-d H:i:s'),
            'remember_token' => '0',
        ]);

        Profile::create([
            'uuid' => \Str::uuid(),
            'first_name' => 'Telemedicine',
            'last_name' => 'Administration',
            'gender' => 'male',
            'user_id' => '1',
            'profile_type' => 'admin',
            'category_id' => null,
            'dob' => null,
            'bio' => null,
            'phone_code' => null,
            'phone_number' => null,
            'phone_verified_at' => date('Y-m-d H:i:s'),
            'ethnicity' => null,
            'nok' => null,
            'emergency_contact' => null,
            'organizations' => null,
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
            'is_convicted' => false,
            'is_policy_holder' => false,
            'language' => 'en',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
