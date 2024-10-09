<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response([ 'users' => 
        UserResource::collection(resource: $users), 
        'message' => 'Successful'], 200);
    }

    public function show(User $user)
    {
        return response([ 'user' => new 
        UserResource($user), 'message' => 'Success'], 200);

    }

    public function update(Request $request, User $user)
    {

        $user->update($request->all());

        return response([ 'user' => new 
        UserResource($user), 'message' => 'Success'], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response(['message' => 'User deleted']);
    }
}
