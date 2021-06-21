<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\Database\Seeders\CourseCategoriesSeederTableSeeder;
use Modules\User\Database\Seeders\AdminUserSeeder;
use Modules\User\Database\Seeders\TeacherUserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AdminUserSeeder::class);
        $this->call(TeacherUserSeeder::class);
        $this->call(CourseCategoriesSeederTableSeeder::class);
    }
}
