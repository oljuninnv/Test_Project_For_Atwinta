<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Http\Requests\FilterRequest;

class GetWorkersInformationFromDepartmentsController extends Controller
{
    // public function show(Request $request, $id)
    // {
    //     // Находим работника по ID
    //     $workers = Worker::with(['user', 'position', 'department'])->whereRelation(
    //         'department',
    //         'id',
    //         '=',
    //         $id
    //     )->get();

    //     // Если работник не найден, возвращаем 404
    //     if (collect(($workers))->isEmpty()) {
    //         return response()->json(['message' => 'Department not found'], 404);
    //     }

    //     // Получаем название отдела
    //     $departmentName = $workers->first()->department->name;

    //     // Получаем всех сотрудников, относящихся к этому отделу
    //     $employees = Worker::with('user', 'position')
    //         ->where('department_id', $workers->first()->department_id)
    //         ->get()
    //         ->map(function ($employee) {
    //             return [
    //                 'id' => $employee->user_id,
    //                 'worker_id' => $employee->id,
    //                 'name' => $employee->user->name,
    //                 'image' => $employee->user->image,
    //                 'position' => $employee->position->name,
    //                 'adopted_at' => $employee->adopted_at,
    //             ];
    //         });

    //     // Возвращаем данные
    //     return response()->json([
    //         'department' => $departmentName,
    //         'employees' => $employees,
    //     ]);
    // }

    public function show(FilterRequest $request, $id)
{
    $name = $request->get('name');

    // Находим работников по ID отдела)
    $workers = Worker::with(['user', 'position', 'department'])
        ->whereRelation('department', 'id', '=', $id)
        ->when($name, function ($query) use ($name) {
            $query->whereHas('user', function ($userQuery) use ($name) {
                $userQuery->where('name', 'like', '%' . $name . '%');
            });
        })
        ->get();

    // Если работники не найдены, возвращаем 404
    if ($workers->isEmpty()) {
        return response()->json(['message' => 'В отделе нет сотрудников'], 404);
    }

    // Получаем название отдела
    $departmentName = $workers->first()->department->name;

    // Пагинация
    $employees = $this->paginate($workers->map(function ($worker) {
        return [
            'id' => $worker->user_id,
            'worker_id' => $worker->id,
            'name' => $worker->user->name,
            'image' => $worker->user->image,
            'position' => $worker->position->name,
            'adopted_at' => $worker->adopted_at,
        ];
    })->toArray());

    // Возвращаем данные
    return response()->json([
        'department' => $departmentName,
        'employees' => $employees,
    ]);
}
}
