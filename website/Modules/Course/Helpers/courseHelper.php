<?php

/**
 * get course price along with their unit in text form
 *
 * @param mixed $courseModal
 *
 * @return String $currency Symbol along with curreny amount
 */
if (!function_exists('getCoursePriceWithUnit')) {
    function getCoursePriceWithUnit($course){
        if($course->is_course_free){
            $text = 'Free';
        }
        else{
            $amount = $course->price_usd; $symbol = 'USD'; // default to USD

            if($course->price_pkr > 0){
                $symbol = 'PKR';
                $amount = $course->price_pkr;
            }
            else if($course->price_aud > 0){
                $symbol = 'AUD';
                $amount = $course->price_aud;
            }
            else if($course->price_euro > 0){
                $symbol = 'EURO';
                $amount = $course->price_euro;
            }

            $amount = get_padded_number($amount);
            $text = $symbol . $amount;
        }
        // dd(ucwords($text));
        return ucwords($text);
    }
}

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
            if(null != $item->student->parent){
                $student_ids[] = $item->student->parent_id;
            }
        }
        $student_ids = array_unique($student_ids);
        return $student_ids;
    }
}

if(!function_exists('getCourseSlotStudentsIds')){
    /**
     * Get Ids of students against given course_slot
     *
     * @param Course $course
     *
     * @return Array $ids[]
     */
    function getCourseSlotStudentsIds($course_slot){
        // dd($course_slot);
        $student_ids = [];
        foreach ($course_slot as $item) {
            $student_ids[] = $item->student_id;
            if(null != $item->student->parent){
                $student_ids[] = $item->student->parent_id;
            }
        }
        $student_ids = array_unique($student_ids);
        // dd($student_ids);
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
