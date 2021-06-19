<?php

namespace Modules\Payment\Services;

use Illuminate\Http\Request;
use Modules\Payment\Entities\PaymentHistory;
use Modules\Payment\Entities\PaymentViewModel;

class PaymentHistoryService
{
    private $relations = [
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
            return getInternalErrorResponse('No PaymentHistory Found', [], 404, 404);
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
    public function getPaymentHistories(Request $request)
    {
        $models = PaymentHistory::orderBy('created_at', 'DESC');
        // dd($request->all());

        //payee_id
        if(isset($request->payee_id) && ('' != $request->payee_id)){
            $models->where('payee_id', $request->payee_id);
        }

        //ref_id
        if(isset($request->ref_id) && ('' != $request->ref_id)){
            $models->where('ref_id', $request->ref_id);
        }

        // ref_model_name
        if (isset($request->ref_model_name) && ('' != $request->ref_model_name)) {
            $models->where('ref_model_name', 'LIKE', $request->ref_model_name);
        }

        //additional_ref_id
        if(isset($request->additional_ref_id) && ('' != $request->additional_ref_id)){
            $models->where('additional_ref_id', $request->additional_ref_id);
        }
        //additional_ref_model_name
        if (isset($request->additional_ref_model_name) && ('' != $request->additional_ref_model_name)) {
            $models->where('additional_ref_model_name', 'LIKE', $request->additional_ref_model_name);
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
        if(isset($request->stripe_card_id) && ('' != $request->card_stripe_card_idid)){
            $models->where('stripe_card_id', $request->stripe_card_id);
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

        if($request->is_date_range == true)
        {
            if(isset($request->startdate) && ('' != $request->startdate) && isset($request->enddate) && ('' != $request->enddate) )
            {
                $startDate = date('Y-m-d H:i:s', strtotime($request->startdate.' 00:00:00'));
                $endDate = date('Y-m-d H:i:s', strtotime($request->enddate.' 23:59:59'));
                $models->whereBetween('created_at', [$startDate, $endDate]);

            }
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $models = $models->get();
        $total_count = $cloned_models->count();
        foreach ($models as $index => $item) {
            $model = PaymentHistory::where('id', $item->id);
            $relations = $this->relations;
            // $relations = [];
            if($item->additional_ref_model_name == 'courses'){
                $relations = array_merge($relations, ['course']);
                $model->whereHas('course', function ($query) use ($request) {
                    if(!$request->get_all){
                        $query->where('teacher_id', $request->user()->profile_id);
                    }

                    if(isset($request->is_course_free) && ('' != $request->is_course_free)){
                        // $query->where('is_course_free', (boolean)$request->is_course_free);
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

        if ('active' == $request->status) {
            $model->status = 'successfull';
        }
        $model->payee_id = $request->payee_id;

        // additional_ref_id
        if(isset($request->additional_ref_id) && ('' != $request->additional_ref_id)){
            $model->additional_ref_id = $request->additional_ref_id;
        }
        // additional_ref_model_name
        if (isset($request->additional_ref_model_name) && ('' != $request->additional_ref_model_name)) {
            $model->additional_ref_model_name = $request->additional_ref_model_name;
        }

         // stripe_trans_id
         if (isset($request->stripe_trans_id) && ('' != $request->stripe_trans_id)) {
            $model->stripe_trans_id =  $request->stripe_trans_id;
        }

        // stripe_trans_status
        if (isset($request->stripe_trans_status) && ('' != $request->stripe_trans_status)) {
            $model->stripe_trans_status = $request->stripe_trans_status;
        }

        // stripe_card_id
         if(isset($request->stripe_card_id) && ('' != $request->stripe_card_id)){
            $model->stripe_card_id = $request->stripe_card_id;
        }

        //easy_paisa_id
        if(isset($request->easy_paisa_id) && ('' != $request->easy_paisa_id)){
            $model->easy_paisa_id = $request->easy_paisa_id;
        }

        //easy_paisa_trans_id
        if(isset($request->easy_paisa_trans_id) && ('' != $request->easy_paisa_trans_id)){
            $model->easy_paisa_trans_id = $request->easy_paisa_trans_id;
        }

        try {
            $model->save();
            $model = PaymentHistory::where('id', $model->id)->with('enrollment')->first();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }

    public function getEnrollmentPayments(Request $request)
    {
        $models = PaymentViewModel::orderBy('payment_history_created_at', 'ASC');

        if($request->user()->profile_type != 'admin'){
            if($request->user()->profile_type == 'teacher'){
                $models->where('teacher_id', $request->user()->profile_id);
            }
        }
        else{
            if(isset($request->teacher_id) && ('' != $request->teacher_id)){
                $models->where('teacher_id', $request->teacher_id);
            }
        }

        $cloned_models = clone $models;
        $data['total'] = $cloned_models->count();
        $data['models'] = $models->get();

        return getInternalSuccessResponse($data, 'Payment History data Fetched Successfully');
    }

    public function getEnrollmentPaymentGraphData(Request $request)
    {
        // dd($request->all());
        $result = $this->getEnrollmentPayments($request);
        if(!$result['status']){
            return $result;
        }
        $history = $result['data'];


        $data = [];
        $data['online'] = [];
        $data['video'] = [];
        if($history['total']){
            foreach ($history['models'] as $item) {
                $month = date('m-Y', strtotime($item->payment_history_created_at));
                if($item->course_nature == 'online'){
                    $data['online'][$month][] = $item->payment_history_amount;
                }
                else{
                    $data['video'][$month][] = $item->payment_history_amount;
                }
            }
        }

        // if(!empty($data['online'] || !empty('video'))){
        //     $
        // }


        // const month_names = [
        //     'January',
        //     'February',
        //     'March',
        //     'April',
        //     'May',
        //     'June',
        // ];
        // const videoCoursesData = [0, 10, 5, 2, 20, 30];
        // const onlineCoursesData = [3, 10, 70, 2, 50, 80];

        return getInternalSuccessResponse($history, 'Payment History Graph data Fetched Successfully');
    }
}
