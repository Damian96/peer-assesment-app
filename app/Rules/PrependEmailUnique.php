<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class PrependEmailUnique implements Rule
{
    /**
     * @var string|null $message
     */
    private $message;

    /**
     * @var bool
     */
    private $existsError = false;

    /**
     * Create a new rule instance.
     *
     * @param null $message
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
        $valid = filter_var($email, FILTER_VALIDATE_EMAIL);
        $exists = !User::whereEmail($email)->exists();
        if ($valid && !$exists) $this->existsError = true;

        return $valid && $exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->existsError ? 'The :attribute already exist!' : 'The :attribute is not a valid email!';
    }
}
