<?php

use App\Router;
use App\Controllers\ProductsController;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\TransactionController;

global $router;

$router->post('api/login', [AuthController::class, 'apiLogin']);
$router->get('api/products', [ProductsController::class, 'apiIndex']);
$router->post('api/products/{id}/purchase', [ProductsController::class, 'apiPurchase']);
