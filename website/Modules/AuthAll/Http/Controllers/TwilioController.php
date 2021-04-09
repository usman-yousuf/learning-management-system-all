<?php

namespace Modules\AuthAll\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Twilio\Rest\Client;


class TwilioController extends Controller
{
	private $client;

	function __construct(){

       	$this->client = new Client(config('AuthAll.config.TWILIO_ACCOUNT_SID'), config('AuthAll.config.TWILIO_AUTH_TOKEN'));
    }

    public function sendMessage($number, $message_body){

    	try {
    		$apiResponse = $this->client->messages->create(
		  		$number, // Text this number
		  		[
		    		// 'from' => (int)'+17147092151', // From a valid Twilio number (babar.kodextech@gmail.com)
		    		'from' => (int)'+14105670100', // live
                    // 'from' => ''
                    // 'from' => 'PNbe53356f882fda1be662b4ae7c6e22a3',
                    // 'from' => (int)$number, // From a valid Twilio number (babar.kodextech@gmail.com)
		    		'body' => $message_body
		  		]
			);
            return getInternalSuccessResponse($apiResponse);

    	} catch (Exception $ex) {
    		return getInternalErrorResponse($ex->getMessage(), $ex->getTraceAsString(), $ex->getCode(), 500);
    	}
    }

    /**
     * Valiate Phone Number
     *
     * @param integer $number
     * @param integer $countrycode
     *
     * @return void
     */
    public function validNumber($number, $countrycode){
    	try {
    		$apiResponse = $this->client->lookups->v1->phoneNumbers($number)->fetch(["countryCode" => $countrycode]);
    		return $apiResponse;

    	} catch (\Exception $e) {
    		return getInternalErrorResponse($e->getMessage(), $e->getTraceAsString(), $e->getCode(), 500);
    	}
    }

}
