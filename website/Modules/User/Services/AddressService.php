<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entitie\Address;
class AddressService 
{
    public function getAddressById($id)
    {
        $address =  Address::where('id', $id)->first();
        if(null == $address){
            return \getInternalErrorResponse('No Address Found', [], 404);
        }
        return getInternalSuccessResponse($address);
    }
}
