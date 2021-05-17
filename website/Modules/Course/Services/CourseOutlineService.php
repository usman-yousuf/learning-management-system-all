<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Course\Entities\CourseOutline;

class CourseOutlineService
{

    /**
     * Check if an Course Outline Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseOutlineById($id)
    {
        $model =  CourseOutline::where('id', $id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No Course Outline Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course Outline against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseOutlineById($id)
    {
        $model =  CourseOutline::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course Outline Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course Outline Exists against given $request->course_outline_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseOutline(Request $request)
    {
        $model = CourseOutline::where('uuid', $request->course_outline_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Outline Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Course Outline against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseOutline(Request $request)
    {
        $model = CourseOutline::where('uuid', $request->course_outline_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course Outline by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseOutline(Request $request)
    {
        $model = CourseOutline::where('uuid', $request->course_outline_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Outline Found', [], 404, 404);
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
     * Get Course Outline based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseOutlines(Request $request)
    {
        $models = CourseOutline::orderBy('created_at');

        //course_outline_uuid
        if(isset($request->course_outline_uuid) && ('' != $request->course_outline_uuid)){
            $models->where('uuid', $request->course_outline_uuid);
        }

        //course_uuid
        // dd($request->course_id);
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        // title
        if(isset($request->title) && ('' != $request->title)){
            $models->where('title', 'LIKE', "%{$request->title}%");
        }

        // duration_hrs
        if (isset($request->duration_hrs) && ('' != $request->duration_hrs)) {
            $models->where('duration_hrs', '=', "{$request->duration_hrs}");
        }

        // duration_mins
        if (isset($request->duration_mins) && ('' != $request->duration_mins)) {
            $models->where('duration_mins', '=', "{$request->duration_mins}");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['course_outlines'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course Outline
     *
     * @param Request $request
     * @param Integer $course_content_id
     * @return void
     */
    public function addUpdateCourseOultine(Request $request, $course_outline_id = null)
    {
        if (null == $course_outline_id) {
            $model = new CourseOutline();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
              //course_uuid
        } else {
            $model = CourseOutline::where('id', $course_outline_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->course_id = $request->course_id;
        $model->title = $request->title;
        $model->duration_hrs = $request->duration_hrs;
        $model->duration_mins = $request->duration_mins;

        // url_link
         if (isset($request->url_link) && ('' != $request->url_link)) {
            $model->url_link = $request->url_link;
        }
        // content_image
        if (isset($request->content_image) && ('' != $request->content_image)) {
            $model->content_image = $request->content_image;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
