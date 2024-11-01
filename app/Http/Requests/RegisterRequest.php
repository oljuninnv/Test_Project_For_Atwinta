<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'type' => 'required|in:front,back',
            'github' => 'nullable|url',
            'city' => 'nullable|string',
            'phone' => 'nullable|string',
            'birthday' => 'nullable|date_format:Y-m-d',
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }
}
