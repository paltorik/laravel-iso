<?php
namespace Language\Iso\Rules;

use Illuminate\Contracts\Validation\Rule;
use Language\Iso\Facades\ISO;

class AvailableLanguage implements Rule
{

    public function passes($attribute, $value)
    {
        return gettype($value)==='string' && strlen($value)===2 && isset(ISO::getAvailableCodes()[$value]);
    }

    public function message(): string
    {
        return "The :attribute not access.";
    }
}
