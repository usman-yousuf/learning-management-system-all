<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\Address;

class AddressService
{

    /**
     * Check if an Address Exists given ID
     *
     * @param Integer $id
     * @return void
     */
    public function getAddressById($id)
    {
        $address =  Address::where('id', $id)->first();
        if(null == $address){
            return \getInternalErrorResponse('No Address Found', [], 404, 404);
        }
        return getInternalSuccessResponse($address);
    }

    /**
     * Check and fetch and Address against given ID
     *
     * @param Integer $id
     * @return void
     */
    public function checkAddressById($id)
    {
        $address =  Address::where('id', $id)->first();
        if(null == $address){
            return getInternalErrorResponse('No Address Found', [], 404, 404);
        }
        return getInternalSuccessResponse($address);
    }

    /**
     * Check if an Address Exists against given $request->address_uuid
     *
     * @param Request $request
     * @return void
     */
    public function checkAddress(Request $request)
    {
        $address = Address::where('uuid', $request->address_uuid)->first();
        if (null == $address) {
            return getInternalErrorResponse('No Address Found', [], 404, 404);
        }
        return getInternalSuccessResponse($address);
    }

    /**
     * Get an Address against given UUID
     *
     * @param Request $request
     * @return void
     */
    public function getAddress(Request $request)
    {
        $address = Address::where('uuid', $request->address_uuid)->first();
        return getInternalSuccessResponse($address);
    }

    /**
     * Delete an Address by given UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteAddress(Request $request)
    {
        $address = Address::where('uuid', $request->address_uuid)->first();
        if (null == $address) {
            return getInternalErrorResponse('No Address Found', [], 404, 404);
        }

        try{
            $address->delete();
        }
        catch(\Exception $ex)
        {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
        }
        return getInternalSuccessResponse($address);
    }

    /**
     * Get Addresses based on filters
     *
     * @param Request $request
     * @return void
     */
    public function getAddresses(Request $request)
    {
        $models = Address::orderBy('created_at');

        if(isset($request->profile_id) && ('' != $request->profile_id)){
            $models->where('profile_id', $request->profile_id);
        }

        // state
        if(isset($request->state) && ('' != $request->state)){
            $models->where('state', 'LIKE', "%{$request->state}%");
        }

        // city
        if (isset($request->city) && ('' != $request->city)) {
            $models->where('city', 'LIKE', "%{$request->city}%");
        }

        // postal_code
        if (isset($request->postal_code) && ('' != $request->postal_code)) {
            $models->where('zip', 'LIKE', "%{$request->postal_code}%");
        }

        $cloned_models = clone $models;
        if(isset($request->offset) && isset($request->limit)){
            $models->offset($request->offset)->limit($request->limit);
        }

        $data['addresses'] = $models->get();
        $data['total_count'] = $cloned_models->count();

        return getInternalSuccessResponse($data);
    }

    /**
     * Add|Update Address
     *
     * @param Request $request
     * @param Integer $address_id
     * @return void
     */
    public function addUpdateAddress(Request $request, $address_id = null)
    {
        if (null == $address_id) {
            $model = new Address();
            $model->uuid = \Str::uuid();
            $model->created_at = date('Y-m-d H:i:s');
            $model->profile_id = $request->user()->profile->id;
        } else {
            $model = Address::where('id', $address_id)->first();
        }
        $model->updated_at = date('Y-m-d H:i:s');

        $model->address1 = $request->address1;
        if (isset($request->address2) && ('' != $request->address2)) { // address2
            $model->address2 = $request->address2;
        }
        $model->city = $request->city;
        $model->country = $request->country;
        $model->zip = $request->post_code;

        if (isset($request->state) && ('' != $request->state)) { // state
            $model->state = $request->state;
        }
        if (isset($request->lat) && ('' != $request->lat)) { // lat
            $model->lat = $request->lat;
        }
       
        if (isset($request->lng) && ('' != $request->lng)) { // lng
            $model->lng = $request->lng;
        }
        if (isset($request->is_default) && ('' != $request->is_default)) { // is_default
            $model->is_default = $request->is_default;
        }

        try {
            $model->save();
            return getInternalSuccessResponse($model);
        } catch (\Exception $ex) {
            return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode());
        }
    }
}
