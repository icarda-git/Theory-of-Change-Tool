<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait RequestHelperTrait {

    public function validateIncomingData($validatorData, $requestData)
    {
        $requestName = request()->route()->getActionName();
        $userID = auth('api')->user()->id;

        $validated = Validator::make($requestData, $validatorData);
        Log::info('Attempting validation on incoming data from method: ' . $requestName . ' from user: ' . $userID);

        switch ($validated->fails()) {
            case true:
                //there were error during validation
                Log::error('There was an error during validation in: ' . $requestName . ' requested from user: ' . $userID);
                return response()->json($validated->errors(), 400);
            case false:
                //everything went as expected during validation
                Log::info('Validation went OK for validation: ' . $requestName . ' for user: ' . $userID);
                return response()->json($validated->validated(), 200);
            default:
                //my code decided it's not a good day to operate
                Log::error('There was an error during validation in: ' . $requestName . ' requested from user: ' . $userID);
                return response()->json('something went kaboom during validation', 500);
        }
    }

    public function checkValidationState($validationResponseCode, $validationResponseBody){
        //We will take the validation response code and move accordingly.

        switch ($validationResponseCode) {
            case 200:
                //validation says the input is OK. Continue to the next step.
                // Which will return a true boolean response
//                return $this->makeRequestToBackend($urlString, $requestType , $header, $validation->getContent());
                return true;
                break;
            case 400:
            case 500:
                //validation stumbled into an error. An error will appear and stop there
//                return response()->json(['error' => $validationResponseBody], 400);
                return false;
        }
    }

    public function makeRequestToBackend($urlString, $requestType, $headerValues=null, $bodyValues=null)
    {
        //headerValues and bodyValues remain null for GET request methods

//        return response()->json(['URL:'=>$urlString,'REQUEST:' => $requestType ,'HEADERS:' => $headerValues , 'BODY:' => $bodyValues]);

        $response = Http::withHeaders($headerValues)->withBody($bodyValues, 'json')->$requestType($urlString)->json();

        return response()->json($response,200);
    }
}
