<?php

/**
 * get categories from Coure Categry Service
 */
if(!function_exists('getCourseCategories')){
    function getCourseCategories()
    {
        $cc = new \Modules\Course\Services\CourseCategoryService();
        $result = $cc->getCourseCategories(app('request'));
        $categories = [];
        if($result['status']){
            $categories = $result['data']['course_categories'];
        }
        return $categories;
    }
}

if(!function_exists('getCourseEnrolledStudentsIds')){
    /**
     * Get Ids of tudents against given course
     *
     * @param Course $course
     *
     * @return Array $ids[]
     */
    function getCourseEnrolledStudentsIds($course){
        $enrollments = $course->enrolledStudents;
        $student_ids = [];
        foreach ($enrollments as $item) {
            $student_ids[] = $item->student_id;
        }
        return $student_ids;
    }
}
