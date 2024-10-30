<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;

class GetDataForWorkerController extends Controller
{
    public function get_informationWithUser()
    {
        // Получаем пользователей с worker_id равным нулю
        $users = User::where('worker_id', null)->get(['id', 'name']);

        // Получаем все отделы
        $departments = Department::all(['id', 'name']);

        // Получаем все позиции
        $positions = Position::all(['id', 'name']);

        // Формируем массив для отправки по API
        $response = [
            'users' => $users,
            'departments' => $departments,
            'positions' => $positions,
        ];

        return response()->json($response);
    }

    public function get_informationWithoutUser()
    {
        // Получаем все отделы
        $departments = Department::all(['id', 'name']);

        // Получаем все позиции
        $positions = Position::all(['id', 'name']);

        // Формируем массив для отправки по API
        $response = [
            'departments' => $departments,
            'positions' => $positions,
        ];

        return response()->json($response);
    }
}
