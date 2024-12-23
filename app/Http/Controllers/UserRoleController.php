<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRole;
use App\Models\Role;
use App\Http\Resources\UserRoleResource;
use App\Enums\RoleEnum;

class UserRoleController extends Controller
{
    // Получение всех записей
    public function index(Request $request)
    {
        $userRoles = UserRole::with(['user', 'role'])->paginate($request->get('per_page'));
        return UserRoleResource::collection($userRoles); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $adminRole = Role::where('name', RoleEnum::ADMIN->value)->first();

        if (!$adminRole) {
            return response()->json(['message' => 'Роль Администратор, не найден'], 404);
        }

        $existingUserRole = UserRole::where('user_id', $request->user_id)->first();

        if ($existingUserRole) {
            $existingUserRole->update(['role_id' => $adminRole->id]);
            return (new UserRoleResource($existingUserRole))->additional(['success' => true]); 
        }

        // Создаем новую запись с role_id Admin
        $userRole = UserRole::create([
            'user_id' => $request->user_id,
            'role_id' => $adminRole->id,
        ]);

        return (new UserRoleResource($userRole))->additional(['success' => true]); 
    }

    // Получение конкретной записи
    public function show($id)
    {
        $userRole = UserRole::with(['user', 'role'])->findOrFail($id);
        return (new UserRoleResource($userRole))->additional(['success' => true]); 
    }

    // Обновление конкретной записи
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'sometimes|required|string|max:255']);

        $userRole = UserRole::find($id);

        if (!$userRole) {
            return response()->json(['message' => 'UserRole not found'], 404);
        }

        $userRole->update($request->all());

        return (new UserRoleResource($userRole))->additional(['success' => true]); 
    }

    // Удаление конкретной записи
    public function destroy($id)
    {
        $userRole = UserRole::findOrFail($id);
        $userRole->delete();
        return response()->json(['message' => 'UserRole deleted succesfully']);
    }
}