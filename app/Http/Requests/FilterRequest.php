<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }
}
