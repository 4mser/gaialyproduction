<?php

if (!function_exists('uploads_path')) {
    function uploads_path($path = '')
    {
        if (strlen(trim(trim($path), '/')) > 0)
            $path = '/' . $path;

        return storage_path('app/uploads' . $path);
    }
}
