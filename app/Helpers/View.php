<?php

namespace App\Helpers;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../../resources/views/{$view}.php";
    }
}
