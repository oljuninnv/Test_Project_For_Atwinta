<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateTestTaskesStatusRequest;
use App\Models\TestTaskStatus;
use App\Http\Resources\TestTaskesStatusResource;

class TestTaskesStatusController extends Controller
{
    public function __construct(protected TestTaskStatus $testTaskStatus)
    {
    }
    public function index(Request $request)
    {
        return TestTaskesStatusResource::collection(TestTaskStatus::with(['user', 'testTask'])->paginate($request->get('per_page')));
    }

    public function show($id)
    {
        $testTaskStatus = TestTaskStatus::find($id);

        if (!$testTaskStatus) {
            return response()->json(['message' => 'Test task status not found'], 404);
        }

        return (new TestTaskesStatusResource($testTaskStatus))->additional(['success' => true]);
    }


    public function update(UpdateTestTaskesStatusRequest $request, $id)
    {
        $testTaskStatus = TestTaskStatus::find($id);

        if (!$testTaskStatus) {
            return response()->json(['message' => 'Test task status not found'], 404);
        }

        // Обновляем поля status и end_date
        $testTaskStatus->update($request->only('status', 'end_date'));

        // Возвращаем обновленное задание
        return (new TestTaskesStatusResource($testTaskStatus))->additional(['success' => true]);
    }
    public function destroy($id)
    {
        $testTaskStatus = TestTaskStatus::find($id);

        if (!$testTaskStatus) {
            return response()->json(['message' => 'Test task Status not found'], 404);
        }

        $testTaskStatus->delete();

        return response()->json(['message' => 'Test task status deleted successfully']);
    }
}
