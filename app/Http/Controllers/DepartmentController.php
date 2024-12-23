<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    public function __construct(protected Department $department)
    {
    }

    public function index(Request $request)
    {
        return DepartmentResource::collection(Department::paginate($request->get('per_page')));
    }

    public function show($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Отдел не найден'], 404);
        }

        return (new DepartmentResource($department))->additional(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $department = Department::create($request->all());

        return (new DepartmentResource($department))->additional(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'sometimes|required|string|max:255']);

        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Отдел не найден'], 404);
        }

        $department->update($request->all());

        return (new DepartmentResource($department))->additional(['success' => true]);
    }

    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Отдел не найден'], 404);
        }

        $department->delete();

        return response()->json(['message' => 'Отдел успешно удалён'], 204); // Успешное удаление
    }
}
