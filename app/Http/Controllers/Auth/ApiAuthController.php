<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function register (Request $request) {
        $string_validate_rule = 'required|string|max:255';
        $validator = Validator::make($request->all(), [
            'username' => $string_validate_rule,
            'firstname' => $string_validate_rule,
            'lastname' => $string_validate_rule,
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:5|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token];
                $status = 200;
            } else {
                $response = ["message" => "Password mismatch"];
                $status = 422;
            }
        } else {
            $response = ["message" =>'User does not exist'];
            $status = 422;
        }
        return response($response, $status);
    }

    public function update (Request $request) {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:5|confirmed',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request->user()->password = Hash::make($request->new_password);
        $request->user()->save();
        $token = $request->user()->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token];
        $status = 200;
        return response($response, $status);
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();

        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
