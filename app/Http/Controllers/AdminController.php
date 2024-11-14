<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\UserResource;

enum RoleEnum: string
{
    case ADMIN = 'Admin';
    case USER = 'User';
    case WORKER = 'Worker';
}

class AdminController extends Controller
{
    public function get_admins(Request $request)
    {
        $name = $request->get('name');

        // Получаем пользователей с ролью Admin
        $usersQuery = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
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
            $query->where('name', 'Worker')->orWhere('name', 'User');
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
        foreach($user->roles as $i) {
            if ($i->name == RoleEnum::ADMIN->value) {
                $user->roles()->detach($i->id);

                if ($user->worker_id) {
                    $user->roles()->attach(Role::where('name', RoleEnum::WORKER->value)->first()->id);
                } else if (!$user->roles->contains('name', RoleEnum::USER)) {
                    $user->roles()->attach(Role::where('name', RoleEnum::USER->value)->first()->id);
                }
                return $this->successResponse('Role deleted successfully');

            }
        }

        return response()->json(['error' => 'User does not have Admin role'], 400);
    }
}
