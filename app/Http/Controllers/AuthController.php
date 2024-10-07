<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {

            $user = Auth::user();
            $success['token'] = $user->createToken('User Token')->accessToken;
            $success['data'] = $user;

            return $this->successResponse($success);
        }

        return $this->failureResponse(['error' => 'Unauthorization or user is not found.']);


    }

    public function register(Request $request)
    {
        $user = new User;
        $user->name = $request->get('name');
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->city = $request->get('city');
        $user->type = $request->get('type');
        $user->birthday = $request->get('birthday');
        $user->github = $request->get('github');
        $user->password = Hash::make($request->get('password'));
        $user->is_finished = true;
        $user->save();
        $token = $user->createToken('Access Token')->accessToken;
        $data = [
            'user' => $user,
            'access_token' => $token,
        ];

        return $this->successResponse($data);
    }
}
