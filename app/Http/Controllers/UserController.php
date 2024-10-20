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
        return $this->successResponse(UserResource::collection($users));
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

    public function store(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'city' => 'nullable|string|max:100',
            'birthday' => 'nullable|date',
            'github' => 'nullable|string|max:255',
            'password' => 'required|string|min:8', // Добавлено поле подтверждения
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Создание нового пользователя
        $user = new User();
        $user->name = $request->get('name');
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->city = $request->get('city');
        $user->type = $request->get('type');
        $user->birthday = $request->get('birthday'); 
        $user->github = $request->get('github');
        $user->password = Hash::make($request->get('password'));
        $user->is_finished = false; // Убедитесь, что это значение корректно
        $user->save();

        return response()->json([
            'user' => new UserResource($user),
            'message' => 'Новый пользователь создан'
        ], 201);
    }
}
