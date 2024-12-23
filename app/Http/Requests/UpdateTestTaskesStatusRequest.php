<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestTaskesStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:в разработке, выполнен, проверен', 
            'end_date' => 'required|date', 
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'Статус - обязательное поле.',
            'status.string' => 'Статус - строка.',
            'status.in' => 'Статус может иметь значения: в разработке, выполнен, проверен',

            'end_date.required' => 'Дата окончания должна быть обязательно.',
            'end_date.date' => 'Дата окончания имеет тип данных дата.',
        ];
    }
}
