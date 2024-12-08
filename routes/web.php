<?php

use App\Router;
use App\Controllers\ProductsController;
use App\Controllers\UserController;
use App\Controllers\AuthController;
use App\Controllers\TransactionController;

$router = new Router();

$router->get('/', [AuthController::class, 'redirect']);

$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout', true]);

// Product routes
$router->get('admin/products', [ProductsController::class, 'index'], true);
$router->get('admin/products/create', [ProductsController::class, 'create'], true);
$router->post('admin/products', [ProductsController::class, 'store'], true);
$router->get('admin/products/{id}/edit', [ProductsController::class, 'edit'], true);
$router->post('admin/products/{id}/update', [ProductsController::class, 'update'], true);
$router->post('admin/products/{id}/delete', [ProductsController::class, 'destroy'], true);

$router->get('products', [ProductsController::class, 'userIndex'], true);
$router->get('transactions', [TransactionController::class, 'userIndex'], true);
$router->post('products/{id}/purchase', [ProductsController::class, 'purchase'], true);

// User routes
$router->get('admin/users', [UserController::class, 'index'], true);
$router->get('admin/users/create', [UserController::class, 'create'], true);
$router->post('admin/users', [UserController::class, 'store'], true);
$router->get('admin/users/{id}/edit', [UserController::class, 'edit'], true);
$router->post('admin/users/{id}/update', [UserController::class, 'update'], true);
$router->post('admin/users/{id}/delete', [UserController::class, 'destroy'], true);

// transactions
$router->get('admin/transactions', [TransactionController::class, 'index'], true);
