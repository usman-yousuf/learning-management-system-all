<?php

namespace Modules\Payment\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\AuthAll\Http\Controllers\API\AuthApiController;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseCategoryService;
use Modules\Course\Services\CourseDetailService;
use Modules\Payment\Services\PaymentHistoryService;
use Modules\User\Services\ProfileService;

class PaymentHistoryController extends Controller
{
    private $commonService;
    private $profileService;
    private $courseDetailService;
    private $paymentHistoryService;

    public function __construct(CommonService $commonService, CourseDetailService $courseDetailService, ProfileService $profileService, PaymentHistoryService $paymentHistoryService)
    {
        // $this->statsService = new StatsService();
        $this->commonService = $commonService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->paymentHistoryService = $paymentHistoryService;
    }

    /**
     * Payment History Report
     *
     * @param Request $request
     * @return void
     */
    public function getPaymentHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_history_uuid' => 'required|exists:payment_histories,uuid',

        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // get listing through course detail
        $result = $this->paymentHistoryService->getPaymentHistory($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $payment_history = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $payment_history);

    }

    /**
     * Delete an Payment History by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deletePaymentHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_history_uuid' => 'required|exists:payment_histories,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an Payment History
        $result = $this->paymentHistoryService->deletePaymentHistory($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $payment_history = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Payment Histories based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getPaymentHistories(Request $request)
    {
        // payee_uuid
        if(isset($request->payee_uuid) && ('' != $request->payee_uuid)){
            $request->merge(['profile_uuid' => $request->payee_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $payee = $result['data'];
            $request->merge(['payee_id' => $payee->id]);
        }

        //ref_uuid
        if(isset($request->ref_uuid) && (''!= $request->ref_uuid)) {
            $request->merge(['course_uuid' => $request->ref_uuid]);
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['ref_id' => $course->id]);
        }

        // payment histories filter
        $result = $this->paymentHistoryService->getPaymentHistories($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $payment_history = $result['data'];
        // dd($payment_history->uuid);
        return $this->commonService->getSuccessResponse('Success', $payment_history);
    }


    /**
     * Add|Update Payment History
     *
     * @param Request $request
     * @return void
     */
    public function updatePaymentHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_history_uuid' => 'exists:payment_histories,uuid',
            'ref_uuid' => 'required|exists:courses,uuid',
            'payee_uuid' => 'required|exists:profiles,uuid',
            'amount' => 'required|numeric',
            'stripe_trans_id' => 'string',
            'stripe_trans_status' => 'string',
            'card_uuid' => 'string|exists:cards,uuid',
            'easypaisa_trans_id' => 'string',
            'easypaisa_trans_status' => 'string',
            'payment_method' => 'required|string',
            'status' => 'required|string',

        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // payee_uuid
        if(isset($request->payee_uuid) && ('' != $request->payee_uuid)){
            $request->merge(['profile_uuid' => $request->payee_uuid]);
            $result = $this->profileService->getProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $payee = $result['data'];
            $request->merge(['payee_id' => $payee->id]);
        }

        //ref_uuid
        if(isset($request->ref_uuid) && (''!= $request->ref_uuid)) {
            $request->merge(['course_uuid' => $request->ref_uuid]);
            $result = $this->courseDetailService->checkCourseDetail($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $course = $result['data'];
            $request->merge(['ref_id' => $course->id]);
        }

        // find payment history by uuid if given
        $payment_history_id = null;
        if(isset($request->payment_history_uuid) && ('' != $request->payment_history_uuid)){
            $result = $this->paymentHistoryService->checkPaymentHistory($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $payment_history = $result['data'];
            $payment_history_id = $payment_history->id;
        }

        DB::beginTransaction();
        $result = $this->paymentHistoryService->addUpdatePaymentHistory($request, $payment_history_id);
        if (!$result['status']) {
            DB::rollBack();
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $course = $result['data'];
        DB::commit();

        return $this->commonService->getSuccessResponse('Success', $course);
    }


}
