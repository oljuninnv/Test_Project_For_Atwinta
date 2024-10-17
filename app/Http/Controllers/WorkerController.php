<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;

class WorkerController extends Controller
{
    // Получить всех работников
    public function index()
    {
        return Worker::all();
    }

    // Добавить нового работника
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:work_positions,id',
            'adopted_at' => 'required|date',
        ]);

        $worker = Worker::create($request->all());

        return response()->json($worker, 201);
    }

    // Найти работника по ID
    public function show($id)
    {
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json(['message' => 'Работник не найден'], 404);
        }

        return response()->json($worker);
    }

    // Обновить поля работника
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:work_positions,id',
            'adopted_at' => 'date',
        ]);

        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json(['message' => 'Работник не найден'], 404);
        }

        $worker->update($request->all());

        return response()->json($worker);
    }

    // Удалить работника по ID
    public function destroy($id)
    {
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json(['message' => 'Работник не найден'], 404);
        }

        $worker->delete();

        return response()->json(['message' => 'Удалённый работник не найден']);
    }
}
