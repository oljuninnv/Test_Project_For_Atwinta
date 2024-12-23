<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Получить правила валидации для запроса.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'login' => 'required|string|unique:users,login',
            'password' => 'required|string|min:6',
            'type' => 'required|in:front,back',
            'github' => 'nullable|regex:/^https?:\/\/github\.com\/[A-Za-z0-9._-]+\/[A-Za-z0-9._-]+$/',
            'city' => 'required|string|max:255',
            'phone' => 'required|phone',
            'birthday' => 'required|date_format:Y-m-d',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ];
    }

    /**
     * Обработка неудачной валидации.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'error' => 'Ошибка в заполнении данных.',
            'messages' => $validator->errors(),
        ]));
    }

    /**
     * Сообщения об ошибках валидации.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'image.mimes' => 'Файл не является изображением',
            'name.required' => 'Имя является обязательным полем.',
            'name.max' => 'Имя не должно быть длиннее 255 символов.',
            'email.required' => 'Email является обязательным полем.',
            'email.email' => 'Некорректный формат email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'password.required' => 'Пароль является обязательным полем.',
            'password.min' => 'Пароль должен содержать минимум 6 символов.',
            'login.required' => 'Логин является обязательным полем.',
            'login.unique' => 'Пользователь с таким логином уже существует.',
            'type.required' => 'Тип пользователя является обязательным полем.',
            'github.regex' => 'Некорректный формат URL для GitHub.',
            'city.required' => 'Город является обязательным полем.',
            'phone.required' => 'Номер телефона является обязательным полем.',
            'phone.phone' => 'Не верный формат ввода номера телефона',
            'birthday.required' => 'Дата рождения является обязательным полем.',
            'birthday.date_format' => 'Дата рождения должна быть в формате Y-m-d.',
        ];
    }

    /**
     * Разрешить запрос.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}