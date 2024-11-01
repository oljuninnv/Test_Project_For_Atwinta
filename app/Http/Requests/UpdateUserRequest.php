<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'show_name' => 'nullable|string',
            'about' => 'required|string',
            'image' => 'nullable|file|image|max:2048', // Максимальный размер 2MB
            'github' => 'nullable|url',
            'city' => 'nullable|string',
            'is_finished' => 'nullable|boolean',
            'telegram' => 'nullable|string',
            'phone' => 'nullable|string',
            'birthday' => 'nullable|date_format:Y-m-d',
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }
}
