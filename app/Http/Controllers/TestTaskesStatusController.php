<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TestTask;
use App\Models\TestTaskStatus;

class TestTaskesStatusController extends Controller
{
    public function __construct(protected TestTaskStatus $testTaskStatus)
    {
    }
    public function index()
    {
        // Получаем все записи TestTaskStatus с загруженными связанными данными
        $testTaskStatuses = TestTaskStatus::with(['user', 'testTask'])->paginate();

        // Формируем массив с нужными данными
        $result = $testTaskStatuses->map(function ($status) {
            return [
                'id' => $status->id,
                'status' => $status->status,
                'end_date' => $status->end_date,
                'start_date' => $status->start_date,
                'user_name' => $status->user->name, // Имя пользователя
                'test_task_name' => $status->testTask->name, // Имя тестового задания
            ];
        });

        return $this->successResponse(
            $this->paginate($result->toArray()) // Пагинация результатов
        );
    }

    public function show($id)
    {

        return $this->successResponse($this->testTaskStatus::find($id));
    }


    public function update(Request $request, $id)
    {
        // Находим тестовое задание по ID
        $testTask = TestTaskStatus::find($id);

        // Проверяем, существует ли тестовое задание
        if (!$testTask) {
            return response()->json(['message' => 'Test task Status not found'], 404);
        }

        // Валидация входящих данных
        $request->validate([
            'status' => 'required|string|max:255', // Валидация для поля status
            'end_date' => 'required|date', // Валидация для поля end_date (может быть пустым)
        ]);

        // Обновляем поля status и end_date
        $testTask->update($request->only('status', 'end_date'));

        return response()->json($testTask);
    }
    public function destroy($id)
    {
        $testTask = TestTaskStatus::find($id);

        if (!$testTask) {
            return response()->json(['message' => 'Test task Status not found'], 404);
        }

        // Удаление задания из базы данных
        $testTask->delete();

        return response()->json(['message' => 'Test task status deleted successfully']);
    }
}
