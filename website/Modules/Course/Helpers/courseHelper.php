<?php

if (!function_exists('getCourseSelectedCurrencyInfo')) {
    /**
     * Selected Curreny info for course
     *
     * @param Model $course
     *
     * @return Array[][] [discount, amount, symbol]
     */
    function getCourseSelectedCurrencyInfo($course)
    {
        $amount = $discount = 0;
        $symbol = '';
        if (!$course->is_course_free) {
            if ($course->price_pkr > 0) {
                $amount = $course->price_pkr;
                $discount = $course->discount_pkr;
                $symbol = 'PKR';
            } else if ($course->price_aud > 0) {
                $amount = $course->price_aud;
                $discount = $course->discount_aud;
                $symbol = 'AUD';
            } else if ($course->price_euro > 0) {
                $amount = $course->price_euro;
                $discount = $course->discount_euro;
                $symbol = 'EURO';
            } else{
                $amount = $course->price_usd;
                $discount = $course->discount_usd;
                $symbol = 'USD';
            }
        }
        $info = ['discount' => $discount, 'amount' => $amount, 'symbol' => strtolower($symbol)];
        // print_array($info);
        return $info;
    }
}

if (!function_exists('getAllApprovedCourses')) {
    /**
     * get All Approved Course
     *
     * @return void
     */
    function getAllApprovedCourses()
    {
        $request = app('request');
        $request->merge(['approved_only' => (int)true]);
        $cds = new \Modules\Course\Services\CourseDetailService();
        $result = $cds->getCourses($request);
        $courses = [];
        if ($result['status']) {
            $courses = $result['data']['courses'];
        }
        return $courses;
    }
}


if (!function_exists('getAllApprovedTeachers')) {
    /**
     * get All Approved Course
     *
     * @return void
     */
    function getAllApprovedTeachers()
    {
        $request = app('request');
        $request->merge(['is_approved_teachers_only' => (int)true]);
        $ps = new \Modules\User\Services\ProfileService();
        $result = $ps->listProfiles($request);
        $profiles = [];
        if ($result['status']) {
            $profiles = $result['data']['models'];
            $total_profiles = $result['data']['total_models'];
        }
        // dd($profiles);
        return $profiles;
    }
}


if (!function_exists('getCoursePriceWithUnit')) {
    /**
     * get course price along with their unit in text form
     *
     * @param mixed $courseModal
     *
     * @return String $currency Symbol along with curreny amount
     */
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

if(!function_exists('getCourseCategories')){
    /**
     * get categories from Coure Categry Service
     *
     * @return void
     */
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
    /**
     * Get Course a Teacher has created so far
     *
     * @return void
     */
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
