<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTestTaskesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:2048',
            'time_limit_in_weeks' => 'required|integer|min:1|max:52',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Поле имени обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 255 символов.',
            
            'file.required' => 'Файл обязателен для загрузки.',
            'file.file' => 'Загруженный объект должен быть файлом.',
            'file.mimes' => 'Файл должен иметь расширение PDF.',
            'file.max' => 'Максимальный размер файла не должен превышать 2 МБ.',
            
            'time_limit_in_weeks.required' => 'Поле ограничения по времени обязательно для заполнения.',
            'time_limit_in_weeks.integer' => 'Ограничение по времени должно быть целым числом.',
            'time_limit_in_weeks.min' => 'Ограничение по времени должно быть не менее 1 недели.',
            'time_limit_in_weeks.max' => 'Ограничение по времени не должно превышать 52 недели.',
        ];
    }
}
