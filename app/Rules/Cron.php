<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cron implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \Cron\CronExpression::isValidExpression($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided cron expression is invalid.';
    }
}
