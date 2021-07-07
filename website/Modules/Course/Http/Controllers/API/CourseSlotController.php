<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Course\Services\CourseSlotService;

class CourseSlotController extends Controller
{
    private $commonService;
    private $courseDetailService;
    private $courseSlot;

    public function __construct(CommonService $commonService, CourseSlotService $courseSlot, CourseDetailService $courseDetailService )
    {
        $this->commonService = $commonService;
        $this->courseSlot = $courseSlot;
        $this->courseDetailService = $courseDetailService;
    }

    /**
     * Get a Single Course Slot based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getCourseSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_slot_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch Course Slot
        $result = $this->courseSlot->checkCourseSLot($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $courseSlot = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $courseSlot);
    }

    /**
     * Delete Slot Outline by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteCourseSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_slot_uuid' => 'required|exists:course_slots,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Course Outline
        $result = $this->courseSlot->deleteCourseSlot($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Course Slots based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getCourseSlots(Request $request)
    {
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->getCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        $result = $this->courseSlot->getCourseSlots($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $course_slot);
    }

    /**
     * Add|Update Slot Outline
     *
     * @param Request $request
     * @return void
     */
    public function updateCourseSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_slot_uuid' => 'exists:course_slots,uuid',
            'course_uuid' => 'required',
            'slot_start' => 'required|date_format:Y-m-d H:i:s',
            'slot_end' => 'required|date_format:Y-m-d H:i:s|after:slot_start',
            'day_nums' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // course_uuid
        if(isset($request->course_uuid) && ('' != $request->course_uuid)){
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['course_id' => $course->id]);
        }

        // find Course Slot by uuid if given
        $course_slot_id = null;
        if(isset($request->course_slot_uuid) && ('' != $request->course_slot_uuid)){
            $result = $this->courseSlot->checkCourseSLot($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course_slot = $result['data'];
            $course_slot_id = $course_slot->id;
        }

        DB::beginTransaction();
        $result = $this->courseSlot->addUpdateCourseSlot($request, $course_slot_id);
        if (!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot = $result['data'];

        DB::commit();
        return $this->commonService->getSuccessResponse('Success', $course_slot);
    }

    /**
     * Update Zoom link
     *
     * @param Request $request
     * @return void
     */
    public function addZoomLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_slot_uuid' => 'required|exists:course_slots,uuid',
            'zoom_link' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        DB::beginTransaction();
        $result = $this->courseSlot->updateZoomLink($request);
        if(!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course_slot_zoom_link = $result['data'];

        DB::commit();
        return $this->commonService->getSuccessResponse('Zoom link added successfully', $course_slot_zoom_link);

    }
}
