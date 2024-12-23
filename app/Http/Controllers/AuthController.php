<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Mail;
use Illuminate\Support\Carbon;
use App\Models\Worker;
use App\Models\TestTask;
use App\Models\TestTaskStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use App\DTO\SendFileDTO;
use App\Enums\RoleEnum;


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
        $user->password = Hash::make($values['password']);
        $user->is_finished = false;
        $user->save();

        // Получение role_id для роли "User"
        $user->roles()->attach(Role::where('name', RoleEnum::USER->value)->first()->id);

        // Подготовка данных для отправки письма
        $email_data = new SendFileDTO(
            $request->email,
            url('storage/test_works/' . $user->type . '.pdf'),
            'Тестовое задание',
            "Пожалуйста, нажмите ниже, чтобы открыть задание"
        );

        // Отправка письма
        Mail::send('orderMail', ['data' => $email_data], function ($message) use ($email_data) {
            $message->to($email_data->email)->subject($email_data->title);
        });

        // Получение тестового задания по типу пользователя
        $testTask = TestTask::where('name', $user->type)->first();

        if ($testTask) {
            // Создание записи в таблице test_task_statuses
            $status = new TestTaskStatus();
            $status->user_id = $user->id;
            $status->test_task_id = $testTask->id;
            $status->status = 'в разработке';
            $status->start_date = Carbon::now();
            $status->end_date = Carbon::now()->addWeeks($testTask->time_limit_in_weeks);
            $status->save();
        }

        // Генерация токена доступа
        $token = $user->createToken('Access Token')->accessToken;
        $data = [
            'user' => $user,
            'access_token' => $token,
        ];

        return response()->json($data, 200);
    }
}
