<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use App\Models\Role;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return $this->successResponse(UserResource::collection($users));
    }

        public function show(User $user)
    {
        return response([ 'user' => new 
        UserResource($user), 'message' => 'Success'], 200);

    }

    public function update(Request $request, User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Валидация данных
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'login' => 'nullable|string|max:255|unique:users,login,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'city' => 'nullable|string|max:100',
            'birthday' => 'nullable|date',
            'github' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Сохраняем старый логин и путь к изображению
        $oldLogin = $user->login;
        $oldImagePath = $user->image;

        // Обновление данных пользователя
        $user->name = $request->get('name');
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->city = $request->get('city');
        $user->birthday = $request->get('birthday');
        $user->github = $request->get('github');
        $user->about = $request->get('about');
        $user->type = $request->get('type');

        if ($user->is_finished == false) {
            $user->is_finished = $request->get('is_finished');
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
            }
            else {
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

    public function store(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'city' => 'nullable|string|max:100',
            'birthday' => 'nullable|date',
            'github' => 'nullable|string|max:255',
            'password' => 'required|string|min:8', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Создание нового пользователя
        $user = new User();
        $user->name = $request->get('name');
        $user->login = $request->get('login');
        $user->email = $request->get('email');
        $user->phone = $request->get('phone');
        $user->city = $request->get('city');
        $user->type = $request->get('type');
        $user->birthday = $request->get('birthday'); 
        $user->github = $request->get('github');
        $user->about = $request->get('about');
        $user->password = Hash::make($request->get('password'));
        $user->is_finished = false; // Убедитесь, что это значение корректно
        
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

        // Получение role_id для роли "User"
        $role = Role::where('name', 'User')->first();

        if ($role) {
            // Запись в UserRole
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);
        }

        return response()->json([
            'user' => new UserResource($user),
            'message' => 'Новый пользователь создан'
        ], 201);
    }
}
