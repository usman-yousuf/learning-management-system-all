<?php

use Modules\Course\Services\CourseCategoryService;

/**
 * get categories from Coure Categry Service
 */
if(!function_exists('getCourseCategories')){
    function getCourseCategories()
    {
        $cc = new CourseCategoryService();
        $result = $cc->getCourseCategories(app('request'));
        $categories = [];
        if($result['status']){
            $categories = $result['data']['course_categories'];
        }
        return $categories;
    }
}
