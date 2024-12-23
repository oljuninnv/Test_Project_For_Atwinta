<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkerRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:work_positions,id',
            'adopted_at' => 'required|date',
        ];
    }

    public function messages(): array
{
    return [
        'user_id.required' => 'Пользователь обязателен для заполнения.',
        'user_id.exists' => 'Выбранный пользователь не существует.',

        'department_id.required' => 'Отдел обязателен для заполнения.',
        'department_id.exists' => 'Выбранный отдел не существует.',

        'position_id.required' => 'Должность обязателен для заполнения.',
        'position_id.exists' => 'Выбранная должность не существует.',

        'adopted_at.required' => 'Дата принятия обязательна для заполнения.',
        'adopted_at.date' => 'Дата принятия должна быть корректной датой.',
    ];
}
}
