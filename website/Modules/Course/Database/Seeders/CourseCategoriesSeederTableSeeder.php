<?php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\Entities\CourseCategory;

class CourseCategoriesSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::statement('truncate table course_categories');
        CourseCategory::insert([
            [
                'uuid' => \Str::uuid(),
                'name' => 'Web Development',
                'description' => 'Web Development using various tech',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid' => \Str::uuid(),
                'name' => 'Web Designing',
                'description' => 'Web Designing using various tech',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'uuid' => \Str::uuid(),
                'name' => 'Graphic Designing',
                'description' => 'Graphic Designing using various tech',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
