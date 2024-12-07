<?php

use App\Router;
use App\Controllers\ProductsController;
use App\Controllers\UserController;
use App\Controllers\AuthController;

$router = new Router();

$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout', true]);
$router->get('/save_user', [AuthController::class, 'save'], true);

// Product routes
$router->get('admin/products', [ProductsController::class, 'index'], true);
$router->get('admin/products/create', [ProductsController::class, 'create'], true);
$router->post('admin/products', [ProductsController::class, 'store'], true);
$router->get('admin/products/{id}/edit', [ProductsController::class, 'edit'], true);
$router->post('admin/products/{id}/update', [ProductsController::class, 'update'], true);
$router->post('admin/products/{id}/delete', [ProductsController::class, 'destroy'], true);

// Product routes
$router->get('admin/users', [UserController::class, 'index'], true);
$router->get('admin/users/create', [UserController::class, 'create'], true);
$router->post('admin/users', [UserController::class, 'store'], true);
$router->get('admin/users/{id}/edit', [UserController::class, 'edit'], true);
$router->post('admin/users/{id}/update', [UserController::class, 'update'], true);
$router->post('admin/users/{id}/delete', [UserController::class, 'destroy'], true);

$router->post('products/{id}/purchase', [ProductsController::class, 'purchase'], true);

return $router;