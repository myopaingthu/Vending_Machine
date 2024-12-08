<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;

session_start();
// Include routes
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/api.php';
global $router;

// Handle the request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->handleRequest($uri, $method);
