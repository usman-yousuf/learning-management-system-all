<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Services\StatsService;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\CourseCategory;

// use Modules\Course\Entities\Course;

class CourseDetailService
{

    /**
     * Check if an Course detail Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseDetailById($id)
    {
        $model =  Course::where('id', $id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No Course detail Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course detail against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseDetailById($id)
    {
        $model =  Course::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course detail Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course content Exists against given $request->course_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseDetail(Request $request)
    {
        $model = Course::where('uuid', $request->course_uuid)
        ->with([
            'teacher'
            , 'category'
            , 'contents'
            , 'handouts'
            , 'outlines'
            , 'slots'
            , 'enrolledStudents'
            , 'reviews'
        ])->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Course Detail against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseDetail(Request $request)
    {
        $model = Course::where('uuid', $request->course_uuid)
            ->with([
                'teacher'
                , 'category'
                , 'contents'
                , 'handouts'
                , 'outlines'
                , 'slots'
                , 'enrolledStudents'
                , 'reviews'
            ])
            ->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course detial by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseDetail(Request $request)
    {
        $model = Course::where('uuid', $request->course_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Found', [], 404, 404);
        }

        try{
            $model->delete();
            $this->updateStats($model->nature, $model->is_coruse_free, $mode="minus", $mode="minus");
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get Courses detail based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourses(Request $request)
    {
       
        $specific_columns =$request->specific_columns;
        
        if(null != $specific_columns)
        {
            $models = Course::where('id', '>', '0')->select($specific_columns)->orderBy('created_at');
        }
        else{
            $models = Course::where('id', '>', '0')->orderBy('created_at');
        }

        // popular courses
        if (isset($request->is_popular) && ('' != $request->is_popular)) {
            $models->orderBy('rating', 'DESC');
        }
        // top courses
        if (isset($request->is_top) && ('' != $request->is_top)) {
            $models->orderBy('students_count', 'DESC');
        }

        $models->orderBy('created_at', 'DESC');

        // rating
        if (isset($request->rating) && ('' != $request->rating)) {
            $models->where('rating', '>=', $request->rating);
        }

        if (isset($request->title) && ('' != $request->title)) {
            $models->where('title', 'LIKE', "%{$request->title}%");
        }

        if (isset($request->start_date) && ('' != $request->start_date)) {
            $models->where('start_date', 'LIKE', $request->start_date);
        }

        if (isset($request->end_date) && ('' != $request->end_date)) {
            $models->where('end_date', 'LIKE', $request->end_date);
        }


        // teacher_id
        if(isset($request->teacher_id) && ('' != $request->teacher_id)){
            $models->where('teacher_id', $request->teacher_id);
        }

        // course_category_id
        if(isset($request->course_category_id) && ('' != $request->course_category_id)){
            $models->where('course_category_id', $request->course_category_id);
        }

        // is_course_free
        if(isset($request->is_course_free) && ('' != $request->is_course_free)){
            $models->where('is_course_free', '=', "{$request->is_course_free}");
        }

        // is_handout_free
        if (isset($request->is_handout_free) && ('' != $request->is_handout_free)) {
            $models->where('is_handout_free', '=', "{$request->is_handout_free}");
        }

        // price_usd
        if (isset($request->price_usd) && ('' != $request->price_usd)) {
            $models->where('price_usd', '=', "{$request->price_usd}");
        }

        //discount_usd
        if (isset($request->discount_usd) && ('' != $request->discount_usd)) {
            $models->where('discount_usd', '=', "{$request->discount_usd}");
        }

        //price_pkr
        if (isset($request->price_pkr) && ('' != $request->price_pkr)) {
            $models->where('price_pkr', '=', "{$request->price_pkr}");
        }
        //discount_pkr
          if (isset($request->discount_pkr) && ('' != $request->discount_pkr)) {
            $models->where('discount_pkr', '=', "{$request->discount_pkr}");
        }

        //total_duration
         if (isset($request->total_duration) && ('' != $request->total_duration)) {
            $models->where('total_duration', '=', "{$request->total_duration}");
        }

         //is_approved
         if (isset($request->is_approved) && ('' != $request->is_approved)) {
            $models->where('is_approved', '=', "{$request->is_approved}");
        }

         //students_count
         if (isset($request->students_count) && ('' != $request->students_count)) {
            $models->where('students_count', '=', "{$request->students_count}");
        }

        //paid_students_count
        if (isset($request->paid_students_count) && ('' != $request->paid_students_count)) {
            $models->where('paid_students_count', '=', "{$request->paid_students_count}");
        }

        //free_students_count
        if (isset($request->free_students_count) && ('' != $request->free_students_count)) {
            $models->where('free_students_count', '=', "{$request->free_students_count}");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['courses'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course detail
     *
     * @param Request $request
     * @param Integer $course_id
     * @return void
     */
    public function addUpdateCourseDetail(Request $request, $course_id = null)
    {
        if (null == $course_id) {
            $model = new Course();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = Course::where('id', $course_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->teacher_id = $request->teacher_id;
        $model->course_category_id = $request->course_category_id;
        $model->nature = $request->nature; //nature; either is video course or online
        $model->is_course_free = $request->is_course_free;  //is_course_free
        $model->start_date = $request->start_date;  //start_date
        $model->title = $request->title;  //title

        if (isset($request->end_date) && ('' != $request->end_date)) {
            $model->end_date = $request->end_date;  //end_date
        }

        if (isset($request->is_handout_free) && ('' != $request->is_handout_free)) {
            $model->is_handout_free = $request->is_handout_free;  //is_handout_free
        }
        if (isset($request->price_usd) && ('' != $request->price_usd)) {
            $model->price_usd = $request->price_usd;  //price_usd
        }
        if (isset($request->discount_usd) && ('' != $request->discount_usd)) {
            $model->discount_usd = $request->discount_usd;  //discount_usd
        }
        if (isset($request->price_pkr) && ('' != $request->price_pkr)) {
            $model->price_pkr = $request->price_pkr;  //price_pkr
        }
        if (isset($request->discount_pkr) && ('' != $request->discount_pkr)) {
            $model->discount_pkr = $request->discount_pkr;  //discount_pkr
        }
        if (isset($request->total_duration) && ('' != $request->total_duration)) {
            $model->total_duration = $request->total_duration;  //total_duration
        }
        if (isset($request->is_approved) && ('' != $request->is_approved)) {
            $model->is_approved = $request->is_approved;  //is_approved
        }
        else{
            $model->is_approved = (int)true;
        }

        if(isset($request->description) && ('' != $request->description)){
            $model->description = $request->description;    // description
        }

         // course_image
         if(isset($request->course_image) && ('' != $request->course_image)){
            $model->course_image = $request->course_image;
         }

        try {
            $model->save();
            $this->updateStats($request->nature, $request->is_course_free);
            $model = Course::where('id', $model->id)->with(['teacher', 'category'])->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    /**
     *  Update Stats Course
     * @param Request $request
     *
     * @return void
     */
    function updateStats($nature, $is_course_free, $mode="add")
    {
        $statsObj = new StatsService();
        $result = $statsObj->addCourseStats($nature, $is_course_free, $mode);
        if(!$result['status']){
            return $result;
        }
        $stats = $result['data'];
        return getInternalSuccessResponse($stats);

    }

    /**
     *  Update Course Stats
     * @param Request $request
     *
     * @return void
     */
    public function updateCourseStats($course_id, $is_free, $mode = "add")
    {
        $model = Course::where('id', $course_id)->first();

        // $model->students_count += 1;
        $model->students_count = ($mode == 'add')? + $model->students_count + 1 : $model->students_count -1;
        if($is_free){
            $model->free_students_count += 1;
        }
        else{
            // $model->paid_students_count += 1;

            $model->paid_students_count = ($mode == 'add')? + $model->paid_students_count + 1 : $model->paid_students_count -1;
            
        }

        // save stats
        try {
            $model->save();
            // dd($model->getAttributes());
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            // dd($ex);
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

}
