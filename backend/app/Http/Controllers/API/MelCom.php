<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\Toc;
use App\Models\TocFlows;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\BadResponseException;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Predis\ClientException;

/*
***What is MelCom and how it works (small documentation per se)***

Knowledge bot is broken so let me explain with words.
(ﾉ◕ヮ◕)ﾉ
*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧
*receiveCode() -> first line of communication. receiveCode takes the request with the information
needed (aka the code MEL provided) and then checks the information inside
the response. If the token is not expired,
we move on to check if the information provided already exists our DB
using getUserData.

*findUser()-> findUser() uses the email as a search parameter to check if our user exists in our DB.

*createUser()-> createUser() uses the array which was made on getUserData() in order to register a new user to our DB.

*getUserData-> getUserData starts by exchanging the token with auth/me in order to get the actual user information.
Once the process is complete, we store the information we need (in an array form) and check if such record exists in our database.
This process is handled from findUser(). Based on the response, either createUser() will be triggered and then a response with all the users data will be returned.

*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧*:･ﾟ✧

I'm pretty sure I forgot something.
COMPLETED: Get the code from React
COMPLETED: Exchange the code for a access_token in react
COMPLETED: Exchange the access_token for user info in /auth/me
COMPLETED: Check if the user already exists in DB
COMPLETED: Create the user if it does not exists in DB
COMPLETED: Add try catch to MEL response
TODO: Once process is complete, move to API/LoginController@Login to login the user (for the backend)
*/

class MelCom extends Controller
{

    //
    public function receiveCode(Request $request)
    {
        $client = new GuzzleHttpClient();


        //check that the incoming request contains a code


        //start the exchange of the code for the token
          $response = $client->request('POST', env('MEL_URL'), [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type: application/json',
                ],
                'form_params' => [
                    'client_id' => env('MEL_CLIENT_ID'),
                    'client_secret' => env('MEL_CLIENT_SECRET'),
                    'code' => $request->code,
                ],
            ]);

        //decode the response from mel that contains the access_token
        $response = (json_decode($response->getBody()));

        $access_token_expiration = $response->expires_in;
        $access_token = $response->access_token;

        //The token will be transferred another function and continue the process
        if (Carbon::now()->timestamp < $access_token_expiration) {
            return $this->getUserData($access_token);
        } else {
            //Or return an error in case the token is expired
            return response()->json(['message' => 'your code must be expired ' . date('m/d/Y H:i:s', $access_token_expiration)], 400);
        }
    }

    public function receiveCodeNew($id)
    {
        $client = new GuzzleHttpClient();


        //check that the incoming request contains a code


        //start the exchange of the code for the token
        $response = $client->request('POST', 'https://api.mel.cgiar.org/v3/auth', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-type: application/json',
            ],
            'form_params' => [
                'client_id' => env('MEL_CLIENT_ID'),
                'client_secret' => env('MEL_CLIENT_SECRET'),
                'code' => $id,
//                'redirect_uri' => env("MEL_REDIRECT_URL"),
            ],
        ]);

        //decode the response from mel that contains the access_token
        $response = (json_decode($response->getBody()));

        $access_token_expiration = $response->expires_in;
        $access_token = $response->access_token;

        //The token will be transferred another function and continue the process
        if (Carbon::now()->timestamp < $access_token_expiration) {
            return $this->getUserData($access_token);
        } else {
            //Or return an error in case the token is expired
            return response()->json(['message' => 'your code must be expired ' . date('m/d/Y H:i:s', $access_token_expiration)], 400);
        }
    }

    public function getUserData($access_token)
    {
        $client = new GuzzleHttpClient();
        //After we have the token, we will exchange it for the actual user data
        $userData = $client->request('GET', 'https://api.mel.cgiar.org/v3/auth/me',
            [
                'headers' =>
                    [
                        'Authorization' => 'Bearer ' . $access_token,
                    ],
            ]);

        //And then store everything in the database of our choice
        ($userResponse = json_decode($userData->getBody(), true));


        $userDataArray = array(
            'name' => $userResponse['first_name'],
            'email' => $userResponse['email'],
            'password' => null,
        );

        //check if user exists based on email
        $userExistanceCheck = $this->findUser($userResponse['email']);

        //Response concerning the status of the request
        //        $userExistanceCheck->status());
        //Response concerning the data of the request
        //        $userExistanceCheck->getData());

        if ($userExistanceCheck->status() == 400) {
            //begin user registration. He doesn't exist
            $this->createUser($userDataArray);

//            $response = $this->createUser()->status();
        }

        //attempt($data,remember_me_bool)
        if (!auth()->attempt($userDataArray,true)) {
            return response()->json(['message' => 'There was an error authenticating your login request'], 400);
        } else {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;

            $user = (object)[
                'id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'role' => auth()->user()->roles[0]->display_name
            ];

            return response()->json(['user' => $user , 'access_token' => $accessToken, 'user_mel_profile' => $userResponse], 201);
        }


        //And then pass the user data back to react
    }

    protected function createUser($data)
    {
        $userRole = Role::where('name', 'user')->first();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->attachRole($userRole);

//        return response()->json($data, 200);
        return response()->json([
            'message' => 'User created',
            'data' => $data],
            200);
    }

    public function findUser($email)
    {
        $userQuery = User::where('email', '=', $email)->get()->last();

        if (!$userQuery) {
            return response()->json(null, 400);
        } else {
            return response()->json($userQuery, 200);
        }
    }


}
