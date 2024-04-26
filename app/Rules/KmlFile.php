<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class KmlFile implements Rule
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
        if (
            $pathinfo['extension'] == 'kml' &&
            ($value->getClientOriginalExtension() == 'kml' || $value->getClientOriginalExtension() == 'xml') &&
            $value->getMimeType() == 'text/xml'
        ) {
            return true;
        } elseif (
            $pathinfo['extension'] == 'kmz' &&
            $value->getClientOriginalExtension() == 'zip' &&
            $value->getMimeType() == 'application/zip'
        ) {
            return true;
        }
        return false;
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
