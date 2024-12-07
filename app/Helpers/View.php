<?php

namespace App\Helpers;

class View
{
    // Method to render a view and pass data to it
    public static function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../../resources/views/{$view}.php";
    }
}
