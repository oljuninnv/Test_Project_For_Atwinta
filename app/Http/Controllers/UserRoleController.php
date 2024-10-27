<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRole;
use App\Models\Worker;
use App\Models\Role;

class UserRoleController extends Controller
{
    // Получение всех записей
    public function index()
    {
        $userRoles = UserRole::with(['user', 'role'])->get();
        return response()->json($userRoles);
    }

    // Добавление новой записи
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'role_id' => 'required|exists:roles,id',
    ]);

    // Проверяем, существует ли запись в таблице user_roles для данного user_id
    $existingUserRole = UserRole::where('user_id', $request->user_id)->first();

    if ($existingUserRole) {
        // Если запись существует, обновляем role_id на 1
        $existingUserRole->update(['role_id' => 1]);
        return response()->json($existingUserRole, 200); // Возвращаем обновлённую запись
    }

    // Если записи не существует, создаём новую
    $userRole = UserRole::create($request->all());
    return response()->json($userRole, 201);
}

    // Получение конкретной записи
    public function show($id)
    {
        $userRole = UserRole::with(['user', 'role'])->findOrFail($id);
        return response()->json($userRole);
    }

    // Обновление конкретной записи
    public function update(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'role_id' => 'required|exists:roles,id',
    ]);

    // Проверяем, существует ли пользователь в таблице Worker
    $isWorker = Worker::where('user_id', $request->user_id)->exists();

    // Устанавливаем role_id в зависимости от результата проверки
    if ($isWorker) {
        $roleId = Role::where('name', 'Worker')->value('id'); // Предполагаем, что роль "Worker" существует
    } else {
        $roleId = Role::where('name', 'User')->value('id'); // Предполагаем, что роль "User" существует
    }

    // Находим и обновляем UserRole
    $userRole = UserRole::findOrFail($id);
    $userRole->update(['role_id' => $roleId, 'user_id' => $request->user_id]);

    return response()->json($userRole);
}

    // Удаление конкретной записи
    public function destroy($id)
    {
        $userRole = UserRole::findOrFail($id);
        $userRole->delete();
        return response()->json(null, 204);
    }
}
