<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Services\NotificationService;
use Modules\Course\Entities\CourseContent;

class CourseContentService
{

    /**
     * Check if an Course content Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseContentById($id)
    {
        $model =  CourseContent::where('id', $id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No Course content Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course Content against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseContentById($id)
    {
        $model =  CourseContent::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Course Content Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course content Exists against given $request->course_contents_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseContent(Request $request)
    {
        $model = CourseContent::where('uuid', $request->course_content_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Content Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Course Content against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseContent(Request $request)
    {
        $model = CourseContent::where('uuid', $request->course_content_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course Content by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseContent(Request $request)
    {
        $model = CourseContent::where('uuid', $request->course_content_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Content Found', [], 404, 404);
        }

        try{
            $model->delete();
            $courseDetailService = new CourseDetailService();
            $result = $courseDetailService->updateCourseContentStats($model->course_id, 'delete');
            if (!$result['status']) {
                return $result;
            }
            $content_stats = $result['data'];

        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get Course Content based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseContents(Request $request)
    {
        $models = CourseContent::orderBy('created_at');

        //course_id
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

        // content_image
        if (isset($request->content_image) && ('' != $request->content_image)) {
            $models->where('content_image', 'LIKE', "%{$request->content_image}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['course_contents'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course Content
     *
     * @param Request $request
     * @param Integer $course_content_id
     * @return void
     */
    public function addUpdateCourseContent(Request $request, $course_content_id = null)
    {
        if (null == $course_content_id) {
            $model = new CourseContent();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = CourseContent::where('id', $course_content_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->title = $request->title;
        $model->course_id = $request->course_id;
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
                //send notification
                $notiService = new NotificationService();
                $receiverIds = getCourseEnrolledStudentsIds($model->course);
                $request->merge([
                    'notification_type' => listNotficationTypes()['add_content']
                    , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'add_content')
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

            if (null == $course_content_id) {
                $result = $courseDetailService->updateCourseContentStats($model->course_id, 'add');
                if (!$result['status']) {
                    return $result;
                }
                $content_stats = $result['data'];
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
