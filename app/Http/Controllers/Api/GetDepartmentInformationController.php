<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\FilterRequest;

class GetDepartmentInformationController extends Controller
{
    public function index(FilterRequest $request)
    {
        $name = $request->get('name');

        // Получаем все отделы с работниками и их позициями
        $query = Department::with(['workers.position']);

        // Если есть поисковый запрос, добавляем условие
        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        // Получаем результаты
        $departments = $query->get()->map(function ($department) {
            return [
                'department_id' => $department->id,
                'department_name' => $department->name,
                'employee_count' => $department->workers->count(),
                'positions' => $department->workers->map(function ($worker) {
                    return [
                        'id' => $worker->position->id,
                        'name' => $worker->position->name,
                    ];
                })->unique('id')->values()->toArray(), // Удаляем дубликаты по id
            ];
        });

        return $this->successResponse(
            $this->paginate($departments->toArray()) // Пагинация результатов
        );
    }
}
