<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class PhoneFormatRule implements Rule
{
    protected ?string $message;

    /**
     * Create a new rule instance.
     *
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $phone = preg_replace('/\(|\)|-|\+|\s/', '', $value);

        return is_numeric($phone) && strlen((string)$phone) === 11;
    }

    public static function handle(): string
    {
        return 'phone';
    }

    public function validate(string $attribute, $value, array $params, Validator $validator): bool
    {
        $handle = $this->handle();

        $validator->setCustomMessages([
            $handle => $this->message(),
        ]);

        return $this->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message ?? 'Phone format of :attribute is wrong';
    }
}