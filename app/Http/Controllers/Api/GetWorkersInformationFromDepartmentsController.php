<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;

class GetWorkersInformationFromDepartmentsController extends Controller
{
    public function show($id)
    {
        // Находим работника по ID
        $workers = Worker::with(['user', 'position', 'department'])->whereRelation('department', 
        'id', '=', $id)->get();

        // Если работник не найден, возвращаем 404
        if (collect(($workers))->isEmpty()) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        // Получаем название отдела
        $departmentName = $workers->first()->department->name;

        // Получаем всех сотрудников, относящихся к этому отделу
        $employees = Worker::with('user', 'position')
            ->where('department_id', $workers->first()->department_id)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->user_id,
                    'worker_id' => $employee->id,
                    'name' => $employee->user->name,
                    'image' => $employee->user->image,
                    'position' => $employee->position->name,
                    'adopted_at' => $employee->adopted_at,
                ];
            });

        // Возвращаем данные
        return response()->json([
            'department' => $departmentName,
            'employees' => $employees,
        ]);
    }
}
