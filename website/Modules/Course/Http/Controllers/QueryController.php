<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\Course\Http\Controllers\API\QueryResponseController;

class QueryController extends Controller
{
    private $commonService;
    private $queryResponseCtrlObj;

    private $statsService;

    public function __construct(
            CommonService $commonService
            , QueryResponseController $queryResponseCtrlObj
    )
    {
        $this->commonService = $commonService;
        $this->queryResponseCtrlObj = $queryResponseCtrlObj;
    }

    /**
     * add|update Query Response
     *
     * @param Request $request
     * @return void
     */
    public function updateQueryResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'query_response_uuid' => 'exists:query_responses,uuid',
            'query_uuid' => 'required|exists:queries,uuid',
            'response_body' => 'required|string',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        $request->merge([
            'responder_uuid' => $request->user()->profile->uuid, 'body' => $request->response_body
        ]);
        $ctrlObj = $this->queryResponseCtrlObj;
        if(null == $request->query_response_uuid || '' ==  $request->query_response_uuid){
            unset($request['query_response_uuid']);
        }

        $apiResponse = $ctrlObj->updateQueryResponse($request)->getData();

        if($apiResponse->status){
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Query Response Saved Successfully', $data) ;
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }

    /**
     * Delete Course Outline
     *
     * @param Request $request
     * @return void
     */
    public function deleteQueryResponse(Request $request)
    {
        $ctrlObj = $this->queryResponseCtrlObj;
        if (null == $request->query_response_uuid || '' ==  $request->query_response_uuid) {
            unset($request['query_response_uuid']);
        }

        $apiResponse = $ctrlObj->deleteQueryResponse($request)->getData();

        if ($apiResponse->status) {
            $data = $apiResponse->data;
            return $this->commonService->getSuccessResponse('Query Response Deleted Successfully', $data);
        }
        // return json_encode($apiResponse);
        return $this->commonService->getProcessingErrorResponse($apiResponse->message, $apiResponse->data, $apiResponse->responseCode, $apiResponse->exceptionCode);
    }
}
