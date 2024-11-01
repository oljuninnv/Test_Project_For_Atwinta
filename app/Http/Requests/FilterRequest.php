<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'query' => 'nullable|string',
            'department_id' => 'nullable|integer',
            'position_id' => 'nullable|integer',
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }
}
