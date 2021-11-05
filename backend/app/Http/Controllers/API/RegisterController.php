<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $request->password = Hash::make($request['password']);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

//        return response()->json(['user' => $user, 'access_token' => $accessToken], 201);
        return response()->json(['message' => 'User registered successfully', 'UserData' => $user, 'UserToken' => $accessToken], 201);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user()
    {
        $teams = auth('api')->user()->userRolesInTeams();
//       return $roles = auth('api')->user()->roles;
        return response()->json(['user' => auth('api')->user()],200);
    }
}
