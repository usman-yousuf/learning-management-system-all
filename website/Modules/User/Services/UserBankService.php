<?php

namespace Modules\User\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\UserBank;

class UserBankService
{

    /**
     * Check if  User Bank Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getUserBankById($id)
    {
        $model =  UserBank::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No User Bank Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check and fetch and User Bank against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkUserBankById($id)
    {
        $model =  UserBank::where('id', $id)->first();
        if(null == $model){
            return getInternalErrorResponse('No User Bank Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Check if an User Bank Exists against given $request->user_bank_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkUserBank(Request $request)
    {
        $model = UserBank::where('uuid', $request->user_bank_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No User Bank Found', [], 404, 404);
        }
        return getInternalSuccessResponse($model);
    }

    /**
     * Get an User Bank against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getUserBank(Request $request)
    {
        $model = UserBank::where('uuid', $request->user_bank_uuid)->first();
        return getInternalSuccessResponse($model);
    }

    /**
     * Delete an User Bank by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteUserBank(Request $request)
    {
        $model = UserBank::where('uuid', $request->user_bank_uuid)->first();
        if (null == $model) {
            return getInternalErrorResponse('No User Bank Found', [], 404, 404);
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
     * Get User Bank based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getUserBanks(Request $request)
    {
        $models = UserBank::orderBy('created_at');
        if(isset($request->profile_id) && ('' != $request->profile_id)){
            $models->where('profile_id', $request->profile_id);
        }

        // bank_name
        if(isset($request->bank_name) && ('' != $request->bank_name)){
            $models->where('bank_name', 'LIKE', "%{$request->bank_name}%");
        }

        // account_title
        if (isset($request->account_title) && ('' != $request->account_title)) {
            $models->where('account_title', 'LIKE', "%{$request->account_title}%");
        }

        // account_number
        if (isset($request->account_number) && ('' != $request->account_number)) {
            $models->where('account_number', '=', "{$request->account_number}");
        }

         // iban
         if (isset($request->iban) && ('' != $request->iban)) {
            $models->where('iban', '=', "{$request->iban}");
        }

        // branch_name
        if (isset($request->branch_name) && ('' != $request->branch_name)) {
            $models->where('branch_name', 'LIKE', "%{$request->branch_name}%");
        }

         // branch_code
         if (isset($request->branch_code) && ('' != $request->branch_code)) {
            $models->where('branch_code', '=', "{$request->branch_code}");
        }

        // swift_code
        if (isset($request->swift_code) && ('' != $request->swift_code)) {
            $models->where('swift_code', '=', "{$request->swift_code}");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['user_banks'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update User Bank
     *
     * @param Request $request
     * @param Integer $user_bank_id
     * @return void
     */
    public function addUpdateUserBank(Request $request, $user_bank_id = null)
    {
        if (null == $user_bank_id) {
            $model = new UserBank();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            $model->profile_id = $request->user()->profile->id;
        } else {
            $model = UserBank::where('id', $user_bank_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->account_title = $request->account_title;
        $model->bank_name = $request->bank_name;
        
        $model->iban = $request->iban;
        $model->account_number = $request->account_number;
        
        $model->branch_name = $request->branch_name;
        $model->branch_code = $request->branch_code;
        $model->swift_code = $request->swift_code;

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
