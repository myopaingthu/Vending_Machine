<?php

namespace App\Helpers;

class Response
{
    public static function redirect(string $url)
    {
        if (php_sapi_name() !== 'cli') {
            header("Location: $url");
            exit();
        }
    }

    public static function withErrors(array $errors, string $redirectUrl)
    {
        $_SESSION['errors'] = $errors;
        self::redirect($redirectUrl);
    }
}
