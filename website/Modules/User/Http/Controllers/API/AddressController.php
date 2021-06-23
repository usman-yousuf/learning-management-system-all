<?php

namespace Modules\User\Http\Controllers\API;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Common\Services\CommonService;
use Modules\User\Services\AddressService;
use Modules\User\Services\ProfileService;

class AddressController extends Controller
{
    private $commonService;
    private $addressService;
    private $profileService;

    public function __construct(CommonService $commonService, AddressService $addressService, ProfileService $profileService)
    {
        $this->commonService = $commonService;
        $this->addressService = $addressService;
        $this->profileService = $profileService;
    }

    /**
     * Get a Single Address based on uuid
     *
     * @param Request $request
     * @return void
     */
    public function getAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_uuid' => 'required',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and fetch an Address
        $result = $this->addressService->checkAddress($request);
        if(!$result['status']){
            return $this->commonService->getProcessingErrorResponse($result['message'], [], 404, 404);
        }
        $address = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $address);
    }

    /**
     * Delete an Address by UUID
     *
     * @param Request $request
     * @return void
     */
    public function deleteAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_uuid' => 'required|exists:addresses,uuid',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // validate and delete an Address
        $result = $this->addressService->deleteAddress($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $address = $result['data'];

        return $this->commonService->getSuccessResponse('Record Deleted Successfully', []);
    }

    /**
     * Get Addresses based on given filters
     *
     * @param Request $request
     * @return void
     */
    public function getAddresses(Request $request)
    {
        if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
            $result = $this->profileService->checkProfile($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $profile = $result['data'];
            $request->merge(['profile_id' => $profile->id]);
        }

        $result = $this->addressService->getAddresses($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $addresses = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $addresses);
    }

    /**
     * Add|Update an Address
     *
     * @param Request $request
     * @return void
     */
    public function updateAddress(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'address_uuid' => 'exists:addresses,uuid',
            // 'is_default' => 'in:0,1',
            'profile_uuid' => 'exists:profiles,uuid',
            'title' => 'string',
            'address1' => 'required|min:5',
            'address2' => 'string',
            'city' => 'required|string',
            'state' => 'string',
            'country' => 'required|string',
            // 'post_code' => 'Rule::requiredIf($request->user()->profile->profile_type)',
            'post_code' => 'string',
            'phone_number' => 'required:min:9'
            // 'lat' => '',
            // 'lng' => '',
        ]);
        if ($validator->fails()) {
            $data['validation_error'] = $validator->getMessageBag();
            return $this->commonService->getValidationErrorResponse($validator->errors()->all()[0], $data);
        }

        // get/set profile uuid
        $uuid = $request->user()->profile->uuid;
        if(isset($request->profile_uuid) && ('' != $request->profile_uuid)){
            $uuid = $request->profile_uuid;
        }
        $request->merge(['profile_uuid' => $uuid]);

        // valiadate and fetch profile
        $result = $this->profileService->getProfile($request);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $profile = $result['data'];
        $request->merge(['profile_id' => $profile->id]);

        // find address by uuid if given
        $address_id = null;
        if(isset($request->address_uuid) && ('' != $request->address_uuid)){
            $result = $this->addressService->checkAddress($request);
            if (!$result['status']) {
                return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
            }
            $address = $result['data'];
            $address_id = $address->id;
        }

        $result = $this->addressService->addUpdateAddress($request, $address_id);
        if (!$result['status']) {
            return $this->commonService->getProcessingErrorResponse($result['message'], $result['data'], $result['responseCode'], $result['exceptionCode']);
        }
        $address = $result['data'];

        return $this->commonService->getSuccessResponse('Success', $address);
    }
}
