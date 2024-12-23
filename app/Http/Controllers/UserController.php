<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\TestTaskStatus;
use App\Enums\RoleEnum;

class UserController extends Controller
{

    public function __construct(protected User $user)
    {
    }

    public function index(Request $request)
    {
        $name = $request->get('name');

        // Создаем запрос к модели User
        $query = User::query();
        
        // Если указано имя, добавляем условие поиска
        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        // Пагинация
        $users = $query->paginate($request->get('per_page'));

        // Возвращаем пагинированный ответ с использованием JsonResource
        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return response([
            'user' => new
                UserResource($user),
            'message' => 'Success'
        ], 200);

    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Сохраняем старый логин и путь к изображению
        $oldLogin = $user->login;
        $oldImagePath = $user->image;

        // Обновление данных пользователя
        $user->name = $request->get('name');
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->telegram = $request->get('telegram');
        $user->city = $request->get('city');
        $user->github = $request->get('github');
        $user->about = $request->get('about');
        $user->type = $request->get('type');

        // Устанавливаем значение is_finished, если оно передано в запросе
        if ($request->has('is_finished')) {
            $user->is_finished = $request->get('is_finished');

            // Проверяем, если пользователь завершил задание
            if ($user->is_finished) {
                // Получаем последний статус тестового задания для пользователя
                $status = TestTaskStatus::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc') // Получаем последний статус
                    ->first();

                // Если статус найден, обновляем его
                if ($status) {
                    $status->status = 'выполнен';
                    $status->save();
                }
            }
        }

        // Устанавливаем значение birthday, если оно передано в запросе
        if ($request->has('birthday') && $user->birthday == null) {
            $user->birthday = $request->get('birthday');
        }

        // Проверяем, изменился ли логин и загружено ли новое изображение
        if ($request->hasFile('image')) {
            // Получаем расширение загружаемого файла
            $extension = $request->file('image')->getClientOriginalExtension();

            // Формируем имя файла
            $newImageName = $user->login . '.' . $extension;

            // Удаляем старое изображение, если логин изменился
            if ($oldLogin !== $user->login) {
                if ($oldImagePath && Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            } else {
                // Если логин не изменился, но есть старое изображение
                if ($oldImagePath && Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            // Сохраняем файл с новым именем
            $imagePath = $request->file('image')->storeAs('users', $newImageName, 'public');

            // Сохраняем путь к изображению в базе данных
            $user->image = $imagePath;
        }

        // Сохраняем обновленные данные
        $user->save();

        return response()->json([
            'user' => new UserResource($user),
            'message' => 'Успешно обновлено'
        ], 200);
    }

    public function destroy(User $user)
    {
        $imagePath = $user->image;

        // Удаляем запись работника, если она существует
        $worker = Worker::where('user_id', $user->id)->first();
        if ($worker) {
            $worker->delete();
        }

        // Удаляем записи в таблице UserRole
        UserRole::where('user_id', $user->id)->delete();

        // Удаляем пользователя
        $user->delete();

        // Удаляем изображение, если оно существует
        if ($imagePath && Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        return response(['message' => 'Пользователь удалён']);
    }

    public function store(RegisterRequest $request)
    {
        $values = $request->all();
        // dd($values);
        // Создание нового пользователя
        $user = new User();
        $user->name = $values['name'];
        $user->login = $values['login'];
        $user->email = $values['email'];
        $user->phone = $values['phone'];
        $user->city = $values['city'];
        $user->type = $values['type'];
        $user->birthday = $values['birthday'];
        $user->github = $values['github'];
        $user->about = $values['about'];
        $user->password = Hash::make($values['password']);
        $user->is_finished = false;

        if ($request->hasFile('image')) {
            // Получаем логин пользователя
            $login = $user->login;

            // Получаем расширение загружаемого файла
            $extension = $request->file('image')->getClientOriginalExtension();

            // Формируем имя файла
            $imageName = $login . '.' . $extension;

            // Сохраняем файл с новым именем
            $imagePath = $request->file('image')->storeAs('users', $imageName, 'public'); // Сохраняем на диск public

            // Сохраняем путь к изображению в базе данных
            $user->image = $imagePath;
        }
        $user->save();

        $user->roles()->attach(Role::where('name', RoleEnum::USER->value)->first()->id);

        return response()->json([
            'user' => new UserResource($user),
            'message' => 'Новый пользователь создан'
        ], 201);
    }
}
