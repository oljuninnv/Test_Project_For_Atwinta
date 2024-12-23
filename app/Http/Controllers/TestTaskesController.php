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

        return $this->successResponse($this->testTask::find($id));
    }

    public function store(AddTestTaskesRequest $request)
    {

        $values = $request->all();

        // Проверка, был ли загружен файл
        if ($request->hasFile('file')) {
            
            $newFileName =  $values['name'].'.pdf';

            // Сохранение файла в storage/test_works с новым именем
            $filePath = $request->file('file')->storeAs('test_works', $newFileName, 'public');

            // Создание нового задания
            $testTask = TestTask::create([
                'name' => $values['name'],
                'file' => $filePath, // Сохраняем путь к файлу
                'time_limit_in_weeks' =>  $values['time_limit_in_weeks']
            ]);

            return response()->json($testTask, 201);
        } else {
            return response()->json(['message' => 'Файл не загружен.'], 400);
        }
    }

    public function update(UpdateTestTaskesRequest $request, $id)
    {
        $values = $request->all();

        $testTask = TestTask::find($id);

        if (!$testTask) {
            return response()->json(['message' => 'Test task not found'], 404);
        }
        
        // Если файл загружен, сохраняем его
        if ($request->hasFile('file')) {
            // Удаляем старый файл, если он существует
            if ($testTask->file) {
                Storage::disk('public')->delete($testTask->file);
            }

            $newFileName = $values['name'].'.pdf';
            // Сохраняем новый файл
            $filePath = $request->file('file')->storeAs('test_works', $newFileName, 'public');
            $testTask->file = $filePath; // Обновляем путь к файлу
        }

        // Обновляем остальные поля
        $testTask->update($request->only('name', 'time_limit_in_weeks'));

        return response()->json($testTask);
    }
    public function destroy($id)
    {
        $testTask = TestTask::find($id);

        if (!$testTask) {
            return response()->json(['message' => 'Test task not found'], 404);
        }

        // Удаление файла из storage/test_tasks
        if ($testTask->file) {
            Storage::disk('public')->delete($testTask->file);
        }

        // Удаление задания из базы данных
        $testTask->delete();

        return response()->json(['message' => 'Test task deleted successfully']);
    }
}
