<?php

namespace App\Rules;

use App\GlobalParams;
use Illuminate\Contracts\Validation\Rule;

class DistanceRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return is_float($value) && $value >= 0 && $value <= GlobalParams::EARTH_RADIUS * GlobalParams::PI;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Дистанция не может быть больше R*PI';
    }
}
