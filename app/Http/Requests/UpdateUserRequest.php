<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'login' => 'required|string|unique:users,login,'.$this->user->id,
            'type' => 'required|in:front,back',
            'github' => 'required|regex:/^https?:\/\/github\.com\/[A-Za-z0-9._-]+\/[A-Za-z0-9._-]+$/',
            'city' => 'required|string|max:255',
            'phone' => 'required|phone',
            'telegram' => 'required|regex:/^https?:\/\/t\.me\/[A-Za-z0-9_]+$/',
            'birthday' => 'required|date_format:Y-m-d',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }

    public function messages()
    {
        return [
            'image.mimes' => 'Файл не является изображением',
            'name.required' => 'Имя является обязательным полем.',
            'name.max' => 'Имя не должно быть длиннее 255 символов.',
            'email.required' => 'Email является обязательным полем.',
            'email.email' => 'Некорректный формат email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'login.required' => 'Логин является обязательным полем.',
            'login.unique' => 'Пользователь с таким логином уже существует.',
            'type.required' => 'Тип пользователя является обязательным полем.',
            'github.regex' => 'Некорректный формат GithUb-ссылки https://github.com/имя_пользователя/название репозитория',
            'city.required' => 'Город является обязательным полем.',
            'phone.required' => 'Номер телефона является обязательным полем.',
            'phone.phone' => 'Не верный формат ввода номера телефона',
            'birthday.required' => 'Дата рождения является обязательным полем.',
            'birthday.date_format' => 'Дата рождения должна быть в формате Y-m-d.',
            'telegram.regex' => 'Некорректный формат Telegram-ссылки. Нужно начинать с https://t.me/',
        ];
    }
}
