<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Http\Requests\FilterRequest;

class GetWorkersInformationFromDepartmentsController extends Controller
{
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
