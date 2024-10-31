<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Worker;

class GetWorkersInformationFromDepartmentsController extends Controller
{
    public function show(Request $request, $id)
    {
        // Находим работника по ID
        $workers = Worker::with(['user', 'position', 'department'])->whereRelation(
            'department',
            'id',
            '=',
            $id
        )->get();

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

//     public function show(Request $request, $id)
// {
//     // Находим работника по ID
//     $workers = Worker::with(['user', 'position', 'department'])->whereRelation(
//         'department',
//         'id',
//         '=',
//         $id
//     )->get();

//     // Если работник не найден, возвращаем 404
//     if ($workers->isEmpty()) {
//         return response()->json(['message' => 'In department have not workers'], 404);
//     }

//     // Получаем название отдела
//     $departmentName = $workers->first()->department->name;

//     // Получаем всех сотрудников, относящихся к этому отделу с пагинацией
//     $perPage = $request->per_page ?: 10; // количество элементов на странице
//     $page = $request->page ?: 1; // номер страницы

//     $employeesQuery = Worker::with('user', 'position')
//         ->where('department_id', $workers->first()->department_id);

//     // Пагинация
//     $employees = $this->paginate($employeesQuery->get()->map(function ($employee) {
//         return [
//             'id' => $employee->user_id,
//             'worker_id' => $employee->id,
//             'name' => $employee->user->name,
//             'image' => $employee->user->image,
//             'position' => $employee->position->name,
//             'adopted_at' => $employee->adopted_at,
//         ];
//     })->toArray());

//     // Возвращаем данные
//     return response()->json([
//         'department' => $departmentName,
//         'employees' => $employees,
//     ]);
// }

//     public function show(Request $request,$id)
// {
//     $name = $request->get('name');
//     $departmentId = $request->get($id); // Получаем department_id из запроса

//     // Получаем всех работников с пользователями, позициями и отделами
//     $query = Worker::with(['user', 'position', 'department']);

//     // Если есть поисковый запрос, добавляем условие
//     if ($name) {
//         $query->whereHas('user', function ($q) use ($name) {
//             $q->where('name', 'like', "%$name%");
//         });
//     }

//     // Если есть department_id, добавляем условие
//     if ($departmentId) {
//         $query->where('department_id', $departmentId); // Фильтруем по department_id
//     }

//     // Получаем результаты
//     $workers = $query->get()->map(function ($worker) {
//         return [
//             'user_id' => $worker->user->id,
//             'worker_id' => $worker->id,
//             'adopted_at' => $worker->adopted_at,
//             'name' => $worker->user->name,
//             'image' => $worker->user->image,
//             'position' => $worker->position->name,
//             'department' => $worker->department->name
//         ];
//     });

//     return $this->successResponse(
//         $this->paginate($workers->toArray()) // Пагинация результатов
//     );
// }
}
