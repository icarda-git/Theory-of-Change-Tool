<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;


class LoginController extends Controller
{


    public function login(Request $request)
    {

        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData)) {
            return response()->json(['message' => 'There was an error authenticating your request'], 400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;


        return response()->json(['user' => auth()->user(), 'access_token' => $accessToken],201);

    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();        return response()->json([
        'message' => 'Successfully logged out',201
    ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json(['user' => $request->user(),201]);
    }
}
