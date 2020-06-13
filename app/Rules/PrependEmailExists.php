<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class PrependEmailExists implements Rule
{
    /**
     * @var string|null $message
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @param string $message
     */
    public function __construct($message = null)
    {
        $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $email = sprintf("%s@%s", $value, config('app.domain'));
        return filter_var($email, FILTER_VALIDATE_EMAIL) && User::whereEmail($email)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ? $this->message : 'The :attribute is not a valid email or does not exist!';
    }
}
