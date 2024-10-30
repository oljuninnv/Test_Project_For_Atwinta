<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\User;
use App\Models\UserRole;

class WorkerController extends Controller
{
    // Получить всех работников
    public function index(Request $request)
    {
        $name = $request->get('name');

        // Получаем всех работников с пользователями, позициями и отделами
        $query = Worker::with(['user', 'position', 'department']);

        // Если есть поисковый запрос, добавляем условие
        if ($name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            });
        }

        // Получаем результаты
        $workers = $query->get()->map(function ($worker) {
            return [
                'worker_id' => $worker->id,
                'adopted_at' => $worker->adopted_at,
                'user' => $worker->user,
                'position' => [
                    'id' => $worker->position->id,
                    'name' => $worker->position->name,
                ],
                'department' => [
                    'id' => $worker->department->id,
                    'name' => $worker->department->name,
                ],
            ];
        });

        return $this->successResponse(
            $this->paginate($workers->toArray()) // Пагинация результатов
        );
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

        // Проверяем, указан ли user_id и существует ли работник с таким user_id
        if ($request->user_id) {
            $existingWorker = Worker::where('user_id', $request->user_id)->first();

            // Если работник с таким user_id уже существует, проверяем, есть ли у User работник
            if ($existingWorker) {
                $user = User::where('id', $request->user_id)->first();
                if ($user && $user->worker_id) {
                    return response()->json(['error' => 'Пользователь с таким user_id уже имеет назначенного работника.'], 409);
                }
            }
        }

        // Создаем нового работника и получаем его идентификатор
        $worker = Worker::create($request->all());

        // Проверяем, указано ли user_id
        if ($request->user_id) {
            $user = User::findOrFail($request->user_id);

            // Обновляем поле worker_id в таблице User
            $user->update(['worker_id' => $worker->id]);

            // Находим соответствующую запись в таблице UserRole
            $role = UserRole::where('user_id', $user->id)->first();
            if ($role) {
                // Если у пользователя есть роль "Worker" (role_id = 1), не нужно ничего менять
                if ($role->role_id == 1) {
                    return response()->json($worker, 201);
                }
                // Обновляем role_id на значение для "Worker"
                $role->update(['role_id' => 3]);
            } else {
                // Создаем новую запись в таблице UserRole
                UserRole::create(['user_id' => $user->id, 'role_id' => 3]);
            }
        }

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

        $user = User::where('worker_id', $id)->first(); // Найдите пользователя с соответствующим worker_id

        if ($user) {
            $user->worker_id = null; // Обнуляем worker_id
            $user->save(); // Сохраняем изменения          
        }

        $userRole = UserRole::where('user_id', $user->id)->first(); // Получаем первую роль пользователя

        if ($userRole && $userRole->role_id != 1) {
            $userRole->update(['role_id' => 2]); // Обновляем role_id
        }

        $worker->delete();

        return response()->json(['message' => 'Работник успешно удалён']);
    }
}