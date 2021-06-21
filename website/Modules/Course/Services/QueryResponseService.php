<?php

namespace Modules\Course\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
