<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\Stats;
use Modules\Common\Services\NotificationService;
use Modules\Course\Entities\CourseHandout;

class HandoutContentService
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
        $model = CourseHandout::where('uuid', $request->handout_content_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Handout Content Found', [], 404, 404);
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
        $model = CourseHandout::where('uuid', $request->handout_content_uuid)->first();
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
        $model = CourseHandout::where('uuid', $request->handout_content_uuid)->first();

        if (null == $model) {
            return getInternalErrorResponse('No Handout Content Found', [], 404, 404);
        }

        try{
            $model->delete();
            $courseDetailService = new CourseDetailService();
            $result = $courseDetailService->updateCourseHandoutStats($model->course_id, 'delete');
            if (!$result['status']) {
                return $result;
            }
            $handout_stats = $result['data'];
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

        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
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
    public function addUpdateCourseHandout(Request $request, $course_handout_id = null)
    {
        if (null == $course_handout_id) {
            $model = new CourseHandout();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = CourseHandout::where('id', $course_handout_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->course_id = $request->course_id;
        $model->title = $request->title;
        $model->url_link = $request->url_link;

        //update handout count

        try {
            $model->save();
            //send notification
            $notiService = new NotificationService();
            $receiverIds = getCourseEnrolledStudentsIds($model->course);
            $request->merge([
                'notification_type' => listNotficationTypes()['handout_content']
                , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'handout_content')
                , 'notification_model_id' => $model->id
                , 'notification_model_uuid' => $model->uuid
                , 'notification_model' => 'handout_contents'

                , 'additional_ref_id' => $model->course->id
                , 'additional_ref_uuid' => $model->course->uuid
                , 'additional_ref_model_name' => 'courses'
            ]);
            $result =  $notiService->sendNotifications($receiverIds, $request, true);
            if(!$result['status'])
            {
                return $result;
            }

            $courseDetailService = new CourseDetailService();
            if(null == $course_handout_id)
            {
                $result = $courseDetailService->updateCourseHandoutStats($model->course_id, 'add');
                if (!$result['status']) {
                    return $result;
                }
                $handout_stats = $result['data'];
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
