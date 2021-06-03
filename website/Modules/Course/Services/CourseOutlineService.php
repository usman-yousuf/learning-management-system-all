<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Entities\Stats;
use Modules\Common\Services\NotificationService;
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
            $courseDetailService = new CourseDetailService();
            $result = $courseDetailService->updateCourseOutlineStats($model->course_id, 'delete');
            if (!$result['status']) {
                return $result;
            }
            $outline_stats = $result['data'];
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
        //course_uuid
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
        } else {
            $model = CourseOutline::where('id', $course_outline_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->course_id = $request->course_id;
        $model->title = $request->title;
        $model->duration_hrs = $request->duration_hrs;
        $model->duration_mins = $request->duration_mins;

        //counter outline stats
        try {
            $model->save();
              //send notification
              $notiService = new NotificationService();
              $stud_ids = $model->course->enrolledStudents;
              $ids = [];
              foreach ($stud_ids as $key => $value) {
                  $ids[] = $value->student_id;
              }
              $receiverIds = $ids;
              $request->merge([
                  'notification_type' => listNotficationTypes()['add_content']
                  , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'add_content')
                  , 'notification_model_id' => $model->id
                  , 'notification_model_uuid' => $model->uuid
                  , 'notification_model' => 'course_outlines'

                  , 'additional_ref_id' => $model->course->id
                  , 'additional_ref_uuid' => $model->course->uuid
                  , 'additional_ref_model_name' => 'courses'
              ]);

            $courseDetailService = new CourseDetailService();
            if(null == $course_outline_id)
            {
                $result = $courseDetailService->updateCourseOutlineStats($model->course_id, 'add');
                if(!$result['status'])
                {
                    return $result;
                }
                $outline_stats = $result['data'];
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
