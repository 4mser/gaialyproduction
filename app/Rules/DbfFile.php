<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DbfFile implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $pathinfo = pathinfo($value->getClientOriginalName());
        return ($pathinfo['extension'] == 'dbf' &&
            $value->getClientOriginalExtension() == 'dbf' &&
            $value->getMimeType() == 'application/x-dbf'
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The file is invalid.');
    }
}
