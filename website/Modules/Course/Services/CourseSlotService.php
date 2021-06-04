<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Modules\Common\Services\NotificationService;
use Modules\Course\Entities\CourseSlot;

class CourseSlotService
{

    /**
     * Check if an Course Slot Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseSlotById($id)
    {
        $model =  CourseSlot::where('id', $id)->with(['course'])->first();
        if(null == $model){
            return getInternalErrorResponse('No Course Slot Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Course Slot against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkCourseSlotById($id)
    {
        $model =  CourseSlot::where('id', $id)->with(['course'])->first();
        if(null == $model){
            return getInternalErrorResponse('No Course SLot Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Course SLot Exists against given $request->course_slot_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkCourseSLot(Request $request)
    {
        $model = CourseSlot::where('uuid', $request->course_slot_uuid)->with(['course'])->first();
        if (null == $model) {
            return getInternalErrorResponse('No Course Slot Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Course slot against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getCourseSlot(Request $request)
    {
        $model = CourseSlot::where('uuid', $request->course_slot_uuid)->with(['course'])->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Course Slot by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseSlot(Request $request)
    {
        $model = CourseSlot::where('uuid', $request->course_slot_uuid)->first();

        if (null == $model) {
            return getInternalErrorResponse('No Course SLot Found', [], 404, 404);
        }

        try{
            $model->delete();
            $courseDetailService = new CourseDetailService();
            $result = $courseDetailService->updateCourseSlotsStats($model->course_id, 'delete');
            if (!$result['status']) {
                return $result;
            }
            $slots_stats = $result['data'];
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get Course Slot based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseSlots(Request $request)
    {
        $models = CourseSlot::orderBy('created_at')->with(['course']);

        if(isset($request->course_slot_uuid) && ('' != $request->course_slot_uuid)){
            $models->where('uuid', $request->course_slot_uuid);
        }

        //course_uuid
        if(isset($request->course_id) && ('' != $request->course_id)){
            $models->where('course_id', $request->course_id);
        }

        // slot_start
        if(isset($request->slot_start) && ('' != $request->slot_start)){
            $models->where('slot_start', '=', "{$request->slot_start}");
        }

        // slot_end
        if (isset($request->slot_end) && ('' != $request->slot_end)) {
            $models->where('slot_end', '=', "{$request->slot_end}");
        }

        // day_nums
        // dd($request->day_nums);
        if (isset($request->day_nums) && ('' != $request->day_nums)) {
            // $dayNums = explode(',', $request->day_nums);
            // dd($dayNums);
            $models->whereIn('day_nums', [$request->day_nums]);
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['course_slots'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Course Slots
     *
     * @param Request $request
     * @param Integer $course_slot_id
     * @return void
     */
    public function addUpdateCourseSlot(Request $request, $course_slot_id = null)
    {
        if (null == $course_slot_id) {
            $model = new CourseSlot();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');

        } else {
            $model = CourseSlot::where('id', $course_slot_id)->first();
            // $model_stats = Stats::orderBy('DESC');

        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->course_id = $request->course_id;
        $model->slot_start = $request->slot_start;
        $model->slot_end = $request->slot_end;
        $model->day_nums = $request->day_nums;

        //counter outline stats

        try {
            $model->save();
            $model = $model->where('id', $model->id)->with(['course'])->first();

            //send notification
            $notiService = new NotificationService();
            $receiverIds = getCourseEnrolledStudentsIds($model->course);
            $request->merge([
                'notification_type' => listNotficationTypes()['course_slot']
                , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'course_slot')
                , 'notification_model_id' => $model->id
                , 'notification_model_uuid' => $model->uuid
                , 'notification_model' => 'course_slots'

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
            if(null == $course_slot_id)
            {
                $result = $courseDetailService->updateCourseSlotsStats($model->course_id, 'add');
                if (!$result['status']) {
                    return $result;
                }
                $slot_stats = $result['data'];
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
