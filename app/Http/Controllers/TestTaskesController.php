<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestTask;
use Illuminate\Support\Facades\Storage;

class TestTaskesController extends Controller
{
    public function __construct(protected TestTask $testTask)
    {
    }
    public function index()
    {
        return $this->successResponse($this->paginate(TestTask::all()->toArray()));

    }

    public function show($id)
    {

        return $this->successResponse($this->testTask::find($id));
    }

    public function store(Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'name' => 'required|string|max:255|min:1',
            'file' => 'required|file|mimes:pdf|max:2048',
            'time_limit_in_weeks' => 'required|integer|min:1|max:52',
        ], [
            'name.required' => 'Поле имени обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 255 символов.',
            'name.min' => 'Имя должно содержать более 1 символа.',
            
            'file.required' => 'Файл обязателен для загрузки.',
            'file.file' => 'Загруженный объект должен быть файлом.',
            'file.mimes' => 'Файл должен иметь расширение PDF.',
            'file.max' => 'Максимальный размер файла не должен превышать 2 МБ.',
            
            'time_limit_in_weeks.required' => 'Поле ограничения по времени обязательно для заполнения.',
            'time_limit_in_weeks.integer' => 'Ограничение по времени должно быть целым числом.',
            'time_limit_in_weeks.min' => 'Ограничение по времени должно быть не менее 1 недели.',
            'time_limit_in_weeks.max' => 'Ограничение по времени не должно превышать 52 недели.',
        ]);

        // Проверка, был ли загружен файл
        if ($request->hasFile('file')) {
            // Сохранение файла в storage/test_tasks
            $newFileName = $request->name.'.pdf'; // Используем str_slug для создания безопасного имени

            // Сохранение файла в storage/test_works с новым именем
            $filePath = $request->file('file')->storeAs('test_works', $newFileName, 'public');

            // Создание нового задания
            $testTask = TestTask::create([
                'name' => $request->name,
                'file' => $filePath, // Сохраняем путь к файлу
                'time_limit_in_weeks' => $request->time_limit_in_weeks
            ]);

            return response()->json($testTask, 201);
        } else {
            return response()->json(['message' => 'Файл не загружен.'], 400);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Валидация входящих данных
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:2048', // Обязательный файл с расширением .pdf
            'time_limit_in_weeks' => 'required|integer|min:1|max:52', // Обязательное поле с числом недель до окончания теста
        ]);

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

            $newFileName = $request->name.'.pdf';
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
