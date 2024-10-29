<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class GetDepartmentInformationController extends Controller
{
    public function index()
    {
        $departments = Department::with(['workers.position'])->get()->map(function ($department) {
            return [
                'department_id' => $department->id,
                'department_name' => $department->name,
                'employee_count' => $department->workers->count(),
                'positions' => $department->workers->pluck('position.name')->unique(),
            ];
        });

        return response()->json($departments);
    }
}
