<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmRequest extends FormRequest
{
    public function rules()
    {
        return [
            'token' => 'required|string|regex:/^\d+-[a-zA-Z0-9]+$/', // Формат токена: %d-%s
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }
}