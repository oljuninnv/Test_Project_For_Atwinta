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
            'type' => 'worker', 
            'github' => null,
            'city' => null,
            'is_finished' => true,
            'phone' => null,
            'birthday' => null,
            'password' => Hash::make('password'), 
            'created_at' => now(),
            'updated_at' => now(),
    ]);

        // Создание связи с ролью Admin в таблице UserRole
        DB::table('user_roles')->insert([
            'user_id' => $userId,
            'role_id' => 1, // ID роли Admin равен 1
        ]);
        // Создание второго пользователя (обычный пользователь)
        $userId2 = DB::table('users')->insertGetId([
            'login' => 'user',
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'image' => null,
            'about' => 'This is a regular user.',
            'type' => 'front', 
            'github' => null,
            'city' => null,
            'is_finished' => false,
            'phone' => null,
            'birthday' => null,
            'password' => Hash::make('user_password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Создание связи с ролью User в таблице UserRole
        DB::table('user_roles')->insert([
            'user_id' => $userId2,
            'role_id' => 2, // ID роли User равна 2
        ]);

        // Создание третьего пользователя (работник отдела разработки)
        $userId3 = DB::table('users')->insertGetId([
            'login' => 'worker_user',
            'name' => 'Regular Worker',
            'email' => 'worker@example.com',
            'image' => null,
            'about' => 'This is a regular worker.',
            'type' => 'front', 
            'github' => null,
            'city' => null,
            'is_finished' => true,
            'phone' => null,
            'birthday' => null,
            'password' => Hash::make('worker_password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Создание связи с ролью Worker в таблице UserRole
        DB::table('user_roles')->insert([
            'user_id' => $userId3,
            'role_id' => 3, // ID роли Worker равен 3
        ]);

        // Добавление работника в таблицу workers
        $workerId = DB::table('workers')->insertGetId([
            'user_id' => $userId3, // Связываем с ID пользователя
            'department_id' => 1, // ID отдела разработки равен 1
            'position_id' => 1, // ID позиции 'Руководитель отдела' равен 1
            'adopted_at' => now(),
        ]);

        // Обновление пользователя с worker_id
        DB::table('users')->where('id', $userId3)->update(['worker_id' => $workerId]);
    
    }
    
}
