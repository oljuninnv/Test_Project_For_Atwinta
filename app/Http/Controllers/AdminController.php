<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\Worker;
use App\Http\Resources\UserResource;

class AdminController extends Controller
{
    public function get_admins(Request $request)
    {
        $name = $request->get('name');

        // Получаем пользователей с ролью Admin
        $usersQuery = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin'); // Предполагается, что поле роли называется 'name'
        });

        if ($name) {
            $usersQuery->where('name', 'like', "%$name%");
        }

        $users = $usersQuery->get();

        return $this->successResponse(
            $this->paginate(
                collect(
                    UserResource::collection(
                        $users,
                    )
                )
                    ->toArray()
            )
        );
    }

    public function get_others(Request $request)
    {
        $name = $request->get('name');

        // Получаем пользователей с ролью Admin
        $usersQuery = User::whereHas('roles', function ($query) {
            $query->where('name', 'Worker')->orWhere('name', 'User'); // Предполагается, что поле роли называется 'name'
        });

        if ($name) {
            $usersQuery->where('name', 'like', "%$name%");
        }

        $users = $usersQuery->get();

        return $this->successResponse(
            collect(
                UserResource::collection(
                    $users,
                )
            )
                ->toArray()
        );
    }

    public function delete_admin($id)
    {
        // Находим пользователя по ID
        $user = User::find($id);

        // Проверяем, существует ли пользователь
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Получаем идентификаторы ролей
        $roleAdminId = Role::where('name', 'Admin')->value('id');
        $roleWorkerId = Role::where('name', 'Worker')->value('id');
        $roleUserId = Role::where('name', 'User')->value('id');

        // Проверяем наличие роли Admin у пользователя
        if ($user->roles->contains('name', 'Admin')) {
            // Удаляем роль Admin
            UserRole::where('role_id', $roleAdminId)
                ->where('user_id', $user->id)
                ->delete();

            // Устанавливаем новую роль в зависимости от наличия worker_id
            if ($user->worker_id) {
                UserRole::updateOrCreate(
                    ['user_id' => $user->id, 'role_id' => $roleWorkerId]
                );
            } elseif (!UserRole::where('user_id', $user->id)->where('role_id', $roleUserId)->exists()) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleUserId,
                ]);
            }

            return $this->successResponse('Role deleted successfully');
        }

        return response()->json(['error' => 'User does not have Admin role'], 400);
    }
}
