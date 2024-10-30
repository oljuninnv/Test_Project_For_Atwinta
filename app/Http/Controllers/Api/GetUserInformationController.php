<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;

class GetUserInformationController extends Controller
{
    public function getUserInfo($worker_id)
    {
        // Получаем работника по worker_id
        $worker = Worker::with(['position', 'department'])
            ->where('id', $worker_id)
            ->first();

        // Если работник не найден, возвращаем ошибку 404
        if (!$worker) {
            return response()->json(['error' => 'Worker not found'], 404);
        }

        // Получаем пользователя, связанного с работником
        $user = User::find($worker->user_id);

        // Если пользователь не найден, возвращаем ошибку 404
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Формируем ответ с необходимыми данными
        return response()->json([
            'image' => $user->image,
            'name' => $user->name,
            'login' => $user->login,
            'email' => $user->email,
            'about' => $user->about,
            'position' => $worker->position ? $worker->position->name : null,
            'department' => $worker->department ? $worker->department->name : null,
            'adopted_at' => $worker->adopted_at,
        ]);
    }
}
