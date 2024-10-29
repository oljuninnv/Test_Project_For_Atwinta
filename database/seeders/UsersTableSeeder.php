<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Создание первого пользователя
        $userId = DB::table('users')->insertGetId([
            'login' => 'admin',
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'image' => null,
            'about' => 'This is the admin user.',
            'type' => 'back', // или 'front', в зависимости от ваших требований
            'github' => null,
            'city' => null,
            'is_finished' => true,
            'phone' => null,
            'birthday' => null,
            'password' => Hash::make('password'), // Не забудьте изменить на более безопасный пароль
            'created_at' => now(),
            'updated_at' => now(),
    ]);

        // Создание связи с ролью Admin в таблице UserRole
        DB::table('user_role')->insert([
            'user_id' => $userId,
            'role_id' => 1, // Предполагается, что ID роли Admin равен 1
        ]);
    }
}
