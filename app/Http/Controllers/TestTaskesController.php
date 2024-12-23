<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestTask;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AddTestTaskesRequest;
use App\Http\Requests\UpdateTestTaskesRequest;
use App\Http\Resources\TestTaskesResource;

class TestTaskesController extends Controller
{
    public function __construct(protected TestTask $testTask)
    {
    }
    public function index(Request $request)
    {
        return TestTaskesResource::collection(TestTask::paginate($request->get('per_page')));

    }

    public function show($id)
    {
        $position = TestTask::find($id);

        if (!$position) {
            return response()->json(['message' => 'Тестовое задание не найдено'], 404);
        }

        return (new TestTaskesResource($position))->additional(['success' => true]);
    }

    public function store(AddTestTaskesRequest $request)
    {
        $values = $request->all();

        // Проверка, был ли загружен файл
        if ($request->hasFile('file')) {
            $newFileName = $values['name'] . '.pdf';

            // Сохранение файла в storage/test_works с новым именем
            $filePath = $request->file('file')->storeAs('test_works', $newFileName, 'public');

            // Создание нового задания
            $testTask = TestTask::create([
                'name' => $values['name'],
                'file' => $filePath,
                'time_limit_in_weeks' => $values['time_limit_in_weeks']
            ]);

            return (new TestTaskesResource($testTask))->additional(['success' => true]);
        } else {
            return response()->json(['message' => 'Файл не загружен.'], 400);
        }
    }

    public function update(UpdateTestTaskesRequest $request, $id)
    {
        $values = $request->all();

        $testTask = TestTask::find($id);

        if (!$testTask) {
            return response()->json(['message' => 'Тестовое задание не найдено'], 404);
        }

        // Если файл загружен, сохраняем его
        if ($request->hasFile('file')) {
            // Удаляем старый файл, если он существует
            if ($testTask->file) {
                Storage::disk('public')->delete($testTask->file);
            }

            $newFileName = $values['name'] . '.pdf';
            $filePath = $request->file('file')->storeAs('test_works', $newFileName, 'public');
            $testTask->file = $filePath; 
        }

        $testTask->update($request->only('name', 'time_limit_in_weeks'));

        return (new TestTaskesResource($testTask))->additional(['success' => true]);
    }

    public function destroy($id)
    {
        $testTask = TestTask::find($id);

        if (!$testTask) {
            return response()->json(['message' => 'Тестовое задание не найдено'], 404);
        }

        if ($testTask->file) {
            Storage::disk('public')->delete($testTask->file);
        }

        $testTask->delete();

        return response()->json(['message' => 'Тестовое задание удалено'], 204); // Успешное удаление
    }
}
