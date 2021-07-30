<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\Services\CommonService;
use Modules\Common\Services\NotificationService;
use Modules\Course\Entities\QueryResponse;

class QueryResponseService
{

    /**
     * Check if an Query Response Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getQueryResponseById($id)
    {
        $model =  QueryResponse::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Query Response Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Query Response against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkQueryResponseById($id)
    {
        $model =  QueryResponse::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Query Response Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Query Response Exists against given $request->course_handout_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkQueryResponse(Request $request)
    {
        $model = QueryResponse::where('uuid', $request->query_response_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Query Response Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Query Response against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getQueryResponse(Request $request)
    {

        $model = QueryResponse::where('uuid', $request->query_response_uuid)->with(['mainQuery','responder','taggedQueryResponse'])->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Query Response by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteQueryResponse(Request $request)
    {
        $model = QueryResponse::where('uuid', $request->query_response_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Query Response Found', [], 404, 404);
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
     * Get Query Response based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getQueryResponses(Request $request)
    {
        $models = QueryResponse::orderBy('created_at');

        //query_id
        if(isset($request->query_id) && ('' != $request->query_id)){
            $models->where('query_id', $request->query_id);
        }

        //responder_id
        if(isset($request->responder_id) && ('' != $request->responder_id)){
        $models->where('responder_id', $request->responder_id);
        }

        // query_response_uuid
        if(isset($request->query_response_uuid) && ('' != $request->query_response_uuid)){
            $models->where('uuid', $request->query_response_uuid);
        }

        // body
        if (isset($request->body) && ('' != $request->body)) {
            $models->where('body', 'LIKE', "%{$request->body}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['query_responses'] = $models->with(['mainQuery','responder','taggedQueryResponse'])->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Query Responses
     *
     * @param Request $request
     * @param Integer $query_response_id
     * @return void
     */
    public function addUpdateQueryResponse(Request $request, $query_response_id = null)
    {
        if (null == $query_response_id) {
            $model = new QueryResponse();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = QueryResponse::where('id', $query_response_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $model->query_id = $request->query_id;
        $model->responder_id = $request->responder_id;
        $model->body = $request->body;
        if(isset($request->tagged_response_id) && ('' != $request->tagged_response_id))
        {
            $model->tagged_response_id = $request->tagged_response_id;
        }

        try {
            $model->save();
            $model = QueryResponse::where('id', $model->id)->with(['mainQuery','responder','taggedQueryResponse'])->first();

            // send notification
            if (null == $query_response_id) {
                $notiService = new NotificationService();
                $receiverIds = [$model->mainQuery->student_id];
                $request->merge([
                    'notification_type' => listNotficationTypes()['respond_query']
                    , 'notification_text' => getNotificationText($request->user()->profile->first_name, 'respond_query')
                    , 'notification_model_id' => $model->id
                    , 'notification_model_uuid' => $model->uuid
                    , 'notification_model' => 'query_responses'
                    , 'additional_ref_id' => $model->mainQuery->id
                    , 'additional_ref_uuid' => $model->mainQuery->uuid
                    , 'additional_ref_model_name' => 'queries'
                    , 'is_activity' => false
                    , 'start_date' => null
                    , 'end_date' => null
                ]);
                $result =  $notiService->sendNotifications($receiverIds, $request, true);
                if (!$result['status']) {
                    return $result;
                }
            }

            // send email
            if (null == $query_response_id) {
                $commonService = new CommonService();
                $result = $commonService->sendQueryResponseEmail($model->mainQuery->student->user->email, 'Query response', 'authall::email_template.query_response', [
                    'message_body'=> $model->mainQuery->body
                    ,'response_body' => $model->body
                    , 'student_name' => $model->mainQuery->student->first_name
                    , 'teacher_name' => $model->responder->first_name
                ]);
                if (!$result['status']) {
                    return $result;
                }
            }
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
