<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\UserResource;
use App\Enums\RoleEnum;

class AdminController extends Controller
{
    public function get_admins(Request $request)
    {
        $name = $request->get('name');

        // Получаем пользователей с ролью Admin
        $usersQuery = User::whereHas('roles', function ($query) {
            $query->where('name', RoleEnum::ADMIN->value);
        });

        if ($name) {
            $usersQuery->where('name', 'like', "%$name%");
        }

        return UserResource::collection($usersQuery->paginate($request->get('per_page')));
    }
    public function get_others(Request $request)
    {
        $name = $request->get('name');

        // Получаем пользователей с ролью Admin
        $usersQuery = User::whereHas('roles', function ($query) {
            $query->where('name', RoleEnum::WORKER->value)->orWhere('name', RoleEnum::USER->value);
        });

        if ($name) {
            $usersQuery->where('name', 'like', "%$name%");
        }

        return UserResource::collection($usersQuery->get());
    }

    public function delete_admin($id)
    {
        
        $user = User::with('roles')->find($id);

        if (!$user) {
            return response()->json(['error' => 'Пользователя не существует'], 404);
        }

        foreach ($user->roles as $role) {
            if ($role->name == RoleEnum::ADMIN->value) {
                $user->roles()->detach($role->id);

                if ($user->worker_id) {
                    $workerRoleId = Role::where('name', RoleEnum::WORKER->value)->first()->id;
                    $user->roles()->attach($workerRoleId);
                } elseif (!$user->roles->contains('name', RoleEnum::USER->value)) {
                    $userRoleId = Role::where('name', RoleEnum::USER->value)->first()->id;
                    $user->roles()->attach($userRoleId);
                }

                return $this->successResponse('Администратор был успешно удалён');
            }
        }

        return response()->json(['error' => 'Пользователь не является администратором'], 400);
    }
}