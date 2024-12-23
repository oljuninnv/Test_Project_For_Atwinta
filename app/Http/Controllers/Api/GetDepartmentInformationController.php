<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\GetDepartmentInformationResource;

class GetDepartmentInformationController extends Controller
{
    public function index(FilterRequest $request)
    {
        $name = $request->get('name');

        $query = Department::with(['workers.position']);

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        // Получаем результаты
        $departments = $query->paginate($request->get('per_page'));

        // Формируем ответ с использованием ресурса
        return GetDepartmentInformationResource::collection($departments);
    }
}
