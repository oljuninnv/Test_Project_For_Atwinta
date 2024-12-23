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

        return $this->successResponse($this->department::find($id));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $department = Department::create($request->all());

        return response()->json($department, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'sometimes|required|string|max:255']);

        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $department->update($request->all());

        return response()->json($department);
    }

    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $department->delete();

        return response()->json(['message' => 'Department deleted successfully']);
    }
}
