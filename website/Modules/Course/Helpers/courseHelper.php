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

if(!function_exists('getTeacherCoursesList')){
    function getTeacherCoursesList()
    {
        $request = app('request');
        $profile_id = $request->user()->profile_id;
        $courseService = new \Modules\Course\Services\CourseDetailService();

        $result = $courseService->getCoursesOnlyByTeacherId($profile_id, 'online');
        if(!$result['status']){
            return [];
        }
        $data = $result['data'];

        $list = [];
        if($data['total_models']){
            foreach($data['models'] as $item){
                $list[$item->uuid] = $item->title;
            }
        }
        return $list;

    }
}
