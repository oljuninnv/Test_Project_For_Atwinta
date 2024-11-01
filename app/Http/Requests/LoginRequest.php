<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }

    public function messages()
    {
        return [
            'email.required' => 'Email обязателен.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'password.required' => 'Пароль обязателен.',
            'password.string' => 'Пароль должен быть строкой.',
        ];
    }
}
