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
        // Проверяем, существует ли пользователь в таблице Worker
        $user = User::where('id', $id)->first();
        $roleAdminId = Role::where('name', '=', 'Admin')->first()->id;
        $roleWorkerId = Role::where('name', '=', 'Worker')->first()->id;
        $roleUserId = Role::where('name', '=', 'User')->first()->id;


        // Устанавливаем role_i;d в зависимости от результата проверки
        if (collect($user->roles)->pluck('name')->contains('Admin')) {
            UserRole::where('role_id', '=', $roleAdminId)->where('user_id', '=', $user->id)->delete();
            if ($user->worker_id) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleWorkerId,
                ]);
            } else if (!UserRole::where('role_id', '=', $roleUserId)->exists()) {
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $roleUserId,
                ]);
            }

            $this->successResponse('Role deleted successfully');
        }

        // Находим и обновляем UserRole

        return response()->json('failrue');
    }
}
