<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Course\Entities\CourseHandout;

class CourseContentService
{

    /**
     * Check if an Handout Content Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseHandoutById($id)
    {
        $model =  CourseHandout::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Handout Content Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Handout Content against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseHandoutById($id)
    {
        $model =  CourseHandout::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Handout Content Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Handout Content Exists against given $request->course_handout_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseHandout(Request $request)
    {
        $model = CourseHandout::where('uuid', $request->course_handout_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Address Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Handout Content against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseHandout(Request $request)
    {
        $model = CourseHandout::where('uuid', $request->course_handout_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Handout Content by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseHandout(Request $request)
    {
        $model = CourseHandout::where('uuid', $request->course_handout_uuid)->first();
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
     * Get Handout Content based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseHandouts(Request $request)
    {
        $models = CourseHandout::orderBy('created_at');

        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $models->where('course_uuid', $request->course_uuid);
        }

        // title
        if(isset($request->title) && ('' != $request->title)){
            $models->where('title', 'LIKE', "%{$request->title}%");
        }

        // url_link
        if (isset($request->url_link) && ('' != $request->url_link)) {
            $models->where('url_link', 'LIKE', "%{$request->url_link}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['handout_contents'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Handout Contents
     *
     * @param Request $request
     * @param Integer $course_handout_uuid
     * @return void
     */
    public function addUpdateCourseHandout(Request $request, $course_handout_uuid = null)
    {
        if (null == $course_handout_uuid) {
            $model = new CourseHandout();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
           //course_uuid
        } else {
            $model = CourseHandout::where('id', $course_handout_uuid)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->title = $request->title;
        $model->url_link = $request->url_link;
        
        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
