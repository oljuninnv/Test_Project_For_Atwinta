<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

enum RoleEnum: string
{
    case ADMIN = 'Admin';
    case USER = 'User';
    case WORKER = 'Worker';
}
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {        
        $values = $request->all();

        // Проверка аутентификации
        if (Auth::attempt(['email' => $values['email'], 'password' => $values['password']])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('User Token')->accessToken;

            // Получаем роли пользователя
            $roles = $user->roles()->pluck('name');

            // Получаем department_id, если пользователь является работником
            $worker = Worker::where('user_id', $user->id)->first();
            $departmentId = $worker ? $worker->department_id : null;

            // Формируем ответ
            $success['data'] = [
                'user' => $user,
                'roles' => $roles,
                'department_id' => $departmentId,
            ];

            return $this->successResponse($success);
        }

        return $this->failureResponse(['error' => 'Ошибка в заполнении данных.']);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        // Валидация данных запроса
        $values = $request->all();
            // Создание нового пользователя
            $user = new User;
            $user->name = $values['name'];
            $user->login = $values['login'];
            $user->email = $values['email'];
            $user->phone = $values['phone'];
            $user->city = $values['city'];
            $user->type = $values['type'];
            $user->birthday = $values['birthday'];
            $user->github = $values['github'];
            $user->password = Hash::make($values['password']);
            $user->is_finished = false;
            $user->save();

            // // Получение role_id для роли "User"
            $user->roles()->attach(Role::where('name', RoleEnum::USER->value)->first()->id);

            // Генерация токена доступа
            $token = $user->createToken('Access Token')->accessToken;
            $data = [
                'user' => $user,
                'access_token' => $token,
            ];

            return response()->json($data, 200);
        } 
}
