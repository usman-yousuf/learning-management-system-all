<?php

namespace Modules\Payment\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Payment\Entities\PaymentHistory;

class PaymentHistoryService
{
    private $relations =[
        'payee'
    ];

    /**
     * Check if an Payment History Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getCourseOutlineById($id)
    {
        $model =  PaymentHistory::where('id', $id)->first();
        if(null == $model){
            return \getInternalErrorResponse('No PaymentHistory Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and Payment History against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkPaymentHistoryById($id)
    {
        $model =  PaymentHistory::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No Payment History Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an Payment History Exists against given $request->course_payment_history_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkPaymentHistory(Request $request)
    {
        $model = PaymentHistory::where('uuid', $request->payment_history_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Payment History Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an Payment History against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getPaymentHistory(Request $request)
    {
        $model = PaymentHistory::where('uuid', $request->payment_history_uuid)->with($this->relations)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an Payment History by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deletePaymentHistory(Request $request)
    {
        $model = PaymentHistory::where('uuid', $request->payment_history_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No Payment History Found', [], 404, 404);
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
     * Get Payment History based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getPaymentHistorys(Request $request)
    {
        $models = PaymentHistory::orderBy('created_at');

        //payment_history_uuid
        if(isset($request->payment_history_uuid) && ('' != $request->payment_history_uuid)){
            $models->where('uuid', $request->payment_history_uuid);
        }

        //payee_id
        if(isset($request->payee_id) && ('' != $request->payee_id)){
            $models->where('payee_id', $request->payee_id);
        }

        //ref_id
        if(isset($request->ref_id) && ('' != $request->ref_id)){
            $models->where('ref_id', $request->ref_id);
        }
        //ref_model_name
        if (isset($request->ref_model_name) && ('' != $request->ref_model_name)) {
            $models->where('ref_model_name', 'LIKE', $request->ref_model_name);
        }

        //additional_ref_id
        if(isset($request->additional_ref_id) && ('' != $request->additional_ref_id)){
            $models->where('additional_ref_id', $request->additional_ref_id);
        }
        //ref_additional_model_name
        if (isset($request->ref_additional_model_name) && ('' != $request->refadditional__model_name)) {
            $models->where('ref_additional_model_name', 'LIKE', $request->ref_additional_model_name);
        }

        // amount
        if(isset($request->amount) && ('' != $request->amount)){
            $models->where('amount', '=' , "{$request->amount}");
        }

        //stripe_trans_id
          if (isset($request->stripe_trans_id) && ('' != $request->stripe_trans_id)) {
            $models->where('stripe_trans_id', 'LIKE', $request->stripe_trans_id);
        }

        //stripe_trans_status
        if (isset($request->stripe_trans_status) && ('' != $request->stripe_trans_status)) {
            $models->where('stripe_trans_status', 'LIKE', $request->stripe_trans_status);
        }

        //card_id
        if(isset($request->card_id) && ('' != $request->card_id)){
            $models->where('card_id', $request->card_id);
        }

        //easy_pasa_id
        if(isset($request->easy_pasa_id) && ('' != $request->easy_pasa_id)){
            $models->where('easy_pasa_id', 'LIKE', $request->easy_pasa_id);
        }

        //easy_pasa_trans_id
         if(isset($request->easy_pasa_trans_id) && ('' != $request->easy_pasa_trans_id)){
            $models->where('easy_pasa_trans_id', 'LIKE', $request->easy_pasa_trans_id);
        }

        // payment_method
        if (isset($request->payment_method) && ('' != $request->payment_method)) {
            $models->where('payment_method', '=', "{$request->payment_method}");
        }

        // status
        if (isset($request->status) && ('' != $request->status)) {
            $models->where('status', '=', "{$request->status}");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }


        $models = $models->get();
        $total_count = $cloned_models->count();
        // \DB::enableQueryLog();
        foreach ($models as $index => $item) {
            // \DB::enableQueryLog();
            $model = PaymentHistory::where('id', $item->id);
            // dd($item->payee_id);
            $relations = $this->relations;
            // $relations = [];
            if($item->ref_model_name == 'courses'){
                $relations = array_merge($relations, ['course']);
                $model->whereHas('course', function ($query) use ($request) {
                    if(!$request->get_all){
                        $query->where('teacher_id', $request->user()->profile_id);
                    }

                    if(isset($request->is_course_free) && ('' != $request->is_course_free)){
                        $query->where('is_course_free', (boolean)$request->is_course_free);
                    }

                    if(isset($request->course_title) && ('' != $request->course_title)){
                        $query->where('title', 'LIKE', "%{$request->course_title}%");
                    }
                });
                $model->with($relations);
            }
            $model = $model->first();
            // dd(\DB::getQueryLog());
            if($model == null){
                unset($models[$index]);
                $total_count--;
            }
            else{
                $models[$index] = $model;
            }
        }

        // dd(\DB::getQueryLog());

        $data['total_count'] = $total_count;
        $data['payment_histories'] = $models;



        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Payment History
     *
     * @param Request $request
     * @param Integer $payment_history_uuid
     * @return void
     */
    public function addUpdatePaymentHistory(Request $request, $payment_history_uuid = null)
    {
        if (null == $payment_history_uuid) {
            $model = new PaymentHistory();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
        } else {
            $model = PaymentHistory::where('id', $payment_history_uuid)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->ref_id = $request->ref_id;
        $model->ref_model_name = $request->ref_model_name;
        $model->amount = $request->amount;
        $model->payment_method = $request->payment_method;
        $model->status = $request->status;
        $model->payee_id = $request->payee_id;

        //additional_ref_id
        if(isset($request->additional_ref_id) && ('' != $request->additional_ref_id)){
            $model->additional_ref_id = $request->additional_ref_id;
        }
        //ref_additional_model_name
        if (isset($request->ref_additional_model_name) && ('' != $request->refadditional__model_name)) {
            $model->ref_additional_model_name= $request->ref_additional_model_name;
        }

         //stripe_trans_id
         if (isset($request->stripe_trans_id) && ('' != $request->stripe_trans_id)) {
            $model->stripe_trans_id =  $request->stripe_trans_id;
        }

        //stripe_trans_status
        if (isset($request->stripe_trans_status) && ('' != $request->stripe_trans_status)) {
            $model->stripe_trans_status = $request->stripe_trans_status;
        }


         //card_id
         if(isset($request->card_id) && ('' != $request->card_id)){
            $model->card_id = $request->card_id;
        }

        //easy_pasa_id
        if(isset($request->easy_pasa_id) && ('' != $request->easy_pasa_id)){
            $model->easy_pasa_id = $request->easy_pasa_id;
        }

        //easy_pasa_trans_id
         if(isset($request->easy_pasa_trans_id) && ('' != $request->easy_pasa_trans_id)){
            $model->easy_pasa_trans_id = $request->easy_pasa_trans_id;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
