<?php

namespace Modules\Course\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Services\CourseDetailService;
use Modules\Course\Services\QueryResponseService;
use Modules\Course\Services\StudentQueryService;
use Modules\User\Services\ProfileService;

class QueryResponseController extends Controller
{
    private $commonService;
    private $queryResponseService;
    private $courseDetailService;
    private $profileService;
    private $studentQueryService;
    public function __construct(CommonService $commonService, QueryResponseService $queryResponseService, CourseDetailService $courseDetailService, ProfileService $profileService, StudentQueryService $studentQueryService )
    {
        $this->commonService = $commonService;
        $this->queryResponseService = $queryResponseService;
        $this->courseDetailService = $courseDetailService;
        $this->profileService = $profileService;
        $this->studentQueryService = $studentQueryService;
    }

    /**
     * Get a Single Query Response  based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getQueryResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query_response_uuid' => 'required|exists:query_responses,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch  Query Response
        $result = $this->queryResponseService->getQueryResponse($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $query_response = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $query_response);
    }

    /**
     * Delete Query Response by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQueryResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query_response_uuid' => 'required|exists:query_responses,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete Query Response
        $result = $this->queryResponseService->deleteQueryResponse($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $query_response = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Handout Student query on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getQueryResponses(Request $request)
    {
        if(isset($request->query_uuid) && ('' != $request->query_uuid)){
            $request->merge(['student_query_uuid' =>  $request->query_uuid]);
            $result = $this->studentQueryService->checkStudentQuery($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student_query = $result['data'];
            $request->merge(['query_id' => $student_query->id]);
        }

        if(isset($request->responder_uuid) && ('' != $request->responder_uuid)){
            $request->merge(['profile_uuid' => $request->responder_uuid]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $responder = $result['data'];
            $request->merge(['responder_id' => $responder->id]);
        }

        $result = $this->queryResponseService->getQueryResponses($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }

    /**
     * Add|Update Query Response
     *
     * @param Request $request
     * @return void
     */
    public function updateQueryResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query_response_uuid' => 'exists:query_responses,uuid',
            'query_uuid' => 'required|exists:queries,uuid',
            'responder_uuid' => 'required|exists:profiles,uuid',
            'body' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // query_uuid
        if(isset($request->query_uuid) && ('' != $request->query_uuid)){
            $request->merge(['student_query_uuid' =>  $request->query_uuid]);
            $result = $this->studentQueryService->checkStudentQuery($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $student_query = $result['data'];
            $request->merge(['query_id' => $student_query->id]);
        }
       
      //responder_uuid
        if(isset($request->responder_uuid) && ('' != $request->responder_uuid)){
            $request->merge(['profile_uuid' => $request->responder_uuid]);
            $result = $this->profileService->checkTeacher($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $responder = $result['data'];
            $request->merge(['responder_id' => $responder->id]);
        }
    

        // find  Query Response by uuid if given
        $query_response_id = null;
        if(isset($request->query_response_uuid) && ('' != $request->query_response_uuid)){
            $result = $this->queryResponseService->checkQueryResponse($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $query_response = $result['data'];
            $query_response_id = $query_response->id;
        }

        $result = $this->queryResponseService->addUpdateQueryResponse($request, $query_response_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $handout_content = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $handout_content);
    }
}
