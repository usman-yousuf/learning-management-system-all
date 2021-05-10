<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\Course;

class CourseContentService
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
        $model = Course::where('uuid', $request->course_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Address Found', [], 404, 404);
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
        $model = Course::where('uuid', $request->course_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course detial by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteAddress(Request $request)
    {
        $model = Course::where('uuid', $request->course_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course detail Found', [], 404, 404);
        }

        try{
            $model->delete();
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
        $models = Course::orderBy('created_at');

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
            $models->where('is_course_free', '=', "%{$request->is_course_free}%");
        }

        // is_handout_free
        if (isset($request->is_handout_free) && ('' != $request->is_handout_free)) {
            $models->where('is_handout_free', '=', "%{$request->is_handout_free}%");
        }

        // price_usd
        if (isset($request->price_usd) && ('' != $request->price_usd)) {
            $models->where('price_usd', '=', "%{$request->price_usd}%");
        }

        //discount_usd
        if (isset($request->discount_usd) && ('' != $request->discount_usd)) {
            $models->where('discount_usd', '=', "%{$request->discount_usd}%");
        }

        //price_pkr
        if (isset($request->price_pkr) && ('' != $request->price_pkr)) {
            $models->where('price_pkr', '=', "%{$request->price_pkr}%");
        }
        //discount_pkr
          if (isset($request->discount_pkr) && ('' != $request->discount_pkr)) {
            $models->where('discount_pkr', '=', "%{$request->discount_pkr}%");
        }

        //total_duration
         if (isset($request->total_duration) && ('' != $request->total_duration)) {
            $models->where('total_duration', '=', "%{$request->total_duration}%");
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
    public function addUpdateAddress(Request $request, $course_id = null)
    {
        if (null == $course_id) {
            $model = new Course();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            //course_cat_id
            //teacher_id
        } else {
            $model = Course::where('id', $course_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->nature = $request->nature;
        $model->is_course_free = $request->is_course_free;
        $model->is_handout_free = $request->is_handout_free;
        $model->price_usd = $request->price_usd;
        $model->discount_usd = $request->discount_usd;
        $model->price_pkr = $request->price_pkr;
        $model->discount_pkr = $request->discount_pkr;
        $model->total_duration = $request->total_duration;
        $model->is_approved = $request->is_approved;

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
