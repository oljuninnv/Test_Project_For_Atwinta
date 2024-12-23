<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
use App\Enums\RoleEnum;
use App\Http\Resources\WorkerResource;

class WorkerController extends Controller
{
    // Получить всех работников
    public function index(Request $request)
    {
        $name = $request->get('name');

        // Создаем запрос к модели Worker с загрузкой связанных данных
        $query = Worker::with(['user', 'position', 'department']);

        // Если есть поисковый запрос, добавляем условие
        if ($name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            });
        }

        // Пагинация
        $workers = $query->paginate($request->get('per_page'));

        // Возвращаем пагинированный ответ с использованием JsonResource
        return WorkerResource::collection($workers);
    }

    // Добавить нового работника
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'department_id' => 'nullable|exists:departments,id',
                'position_id' => 'nullable|exists:work_positions,id',
                'adopted_at' => 'required|date',
            ]);

            // Проверяем, указан ли user_id и существует ли работник с таким user_id
            if ($request->user_id) {
                $existingWorker = Worker::where('user_id', $request->user_id)->first();

                if ($existingWorker) {
                    $user = User::where('id', $request->user_id)->first();
                    if ($user && $user->worker_id) {
                        return response()->json(['error' => 'Пользователь с таким user_id уже имеет назначенного работника.'], 409);
                    }
                }
            }

            // Создаем нового работника
            $worker = Worker::create($request->all());

            if ($request->user_id) {
                $user = User::findOrFail($request->user_id);
                $user->update(['worker_id' => $worker->id]);

                $role = UserRole::where('user_id', $user->id)->first();
                if ($role) {
                    if ($role->role_id == Role::where('name', RoleEnum::WORKER->value)->first()->id) {
                        return response()->json($worker, 201);
                    } else if ($role->role_id == Role::where('name', RoleEnum::ADMIN->value)->first()->id) {
                        $user->roles()->attach(Role::where('name', RoleEnum::WORKER->value)->first()->id);
                    } else {
                        $user->roles()->update(['role_id' => Role::where('name', RoleEnum::WORKER->value)->first()->id]);
                    }
                } else {
                    $user->roles()->attach(Role::where('name', RoleEnum::WORKER->value)->first()->id);
                }
            }

            return response()->json($worker, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

        // Получаем все роли пользователя
        $userRoles = UserRole::where('user_id', $user->id)->get();

        foreach ($userRoles as $userRole) {
            // Проверяем, является ли роль администратора
            if ($userRole->role_id != Role::where('name', RoleEnum::ADMIN->value)->first()->id) {
                $user->roles()->detach($userRole->role_id); // Удаляем роль пользователя
                $user->roles()->attach(Role::where('name', RoleEnum::USER->value)->first()->id); // Добавляем роль USER
            }
        }

        $hasAdminRole = $userRoles->contains(function ($userRole) {
            return $userRole->role_id == Role::where('name', RoleEnum::ADMIN->value)->first()->id;
        });

        // Если роли ADMIN нет, добавляем роль USER
        if (!$hasAdminRole) {
            $user->roles()->attach(Role::where('name', RoleEnum::USER->value)->first()->id);
        }


        $worker->delete();

        return response()->json(['message' => 'Работник успешно удалён']);
    }
}